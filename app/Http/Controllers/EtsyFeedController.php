<?php

namespace App\Http\Controllers;

use App\Dto\ListingStateEnum;
use App\Models\Feed;
use XMLWriter;

class EtsyFeedController extends Controller
{
    public function __invoke(Feed $feed)
    {
        $xml = new XMLWriter();

        $xml->openMemory();
        $xml->startDocument("1.0");
        $xml->startElement("rss");
            $xml->writeAttribute('version','2.0');
            $xml->writeAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
            $xml->startElement('channel');
                $xml->startElement('created_at');
                    $xml->text(now()->format('Y-m-d H:i'));
                $xml->endElement();

                foreach($feed->items()->where('state', ListingStateEnum::active)->get() as $item) {
                    $xml->startElement('item');
                        $this->addXmlElement($xml, 'g:id', $item->listing_id);
                        $this->addXmlElement($xml, 'title', substr($item->title, 0,149) );
                        $this->addXmlElement($xml, 'description', $item->description);
                        $this->addXmlElement($xml, 'g:image_link', $item->image_url);
                        $this->addXmlElement($xml, 'g:availability', $item->quantity > 0 ? 'in stock' : 'out of stock');
                        foreach( $item->images as $i => $image ) {
                            if($i > 0) {
                                $this->addXmlElement($xml, 'g:additional_image_link', $image['url_fullxfull']);
                            }
                        }
                        $this->addXmlElement($xml, 'g:link', route('etsy.feed.redirect', [
                            'feed' => $feed,
                            'listingId' => $item->listing_id,
                        ]) );
                        $this->addXmlElement($xml, 'g:condition', 'new');
                        $this->addXmlElement($xml, 'g:brand', $feed->brand_name);
                        $this->addXmlElement($xml, 'g:price', $item->price);
                        $this->addXmlElement($xml, 'g:google_product_category', $feed->google_product_category);

                        foreach($item->data->shipping_profile->shipping_profile_destinations as $shippingProfile) {
                            $xml->startElement('g:shipping');
                                $this->addXmlElement($xml, 'g:country', $shippingProfile->destination_country_iso);
                                $this->addXmlElement($xml, 'g:price', (string) $shippingProfile->primary_cost);
                                if($item->data->shipping_profile->hasProcessingDays()) {
                                    $this->addXmlElement($xml, 'g:min_handling_time', $item->data->shipping_profile->min_processing_days);
                                    $this->addXmlElement($xml, 'g:max_handling_time', $item->data->shipping_profile->max_processing_days);
                                }
                                if($shippingProfile->hasDeliveryDays()) {
                                    $this->addXmlElement($xml, 'g:min_transit_time', $shippingProfile->min_delivery_days);
                                    $this->addXmlElement($xml, 'g:max_transit_time', $shippingProfile->max_delivery_days);
                                } elseif ($shippingProfile->isDomestic()) {
                                    $this->addXmlElement($xml, 'g:min_transit_time', 1);
                                    $this->addXmlElement($xml, 'g:max_transit_time', 5);
                                }
                            $xml->endElement();
                        }
                    $xml->endElement();
                }

            $xml->endElement();
        $xml->endElement();

        $xml->endDocument();

        return response($xml->flush(), headers: [
            'Content-Type' => 'application/xml',
        ]) ;
    }

    private function addXmlElement(
        XmlWriter $xml,
        string $name,
        string $value
    ): void
    {
        $xml->startElement($name);
        $xml->text($value);
        $xml->endElement();
    }
}
