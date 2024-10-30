<?php

/**
 * transit days
 */
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists("EnUpsSmallTransitDays")) {

    class EnUpsSmallTransitDays
    {

        public function estimated_arrival_days($EstimatedArrival)
        {
            $Arrival = (isset($EstimatedArrival->Arrival->Date)) ? $EstimatedArrival->Arrival->Date : "";
            $Pickup = (isset($EstimatedArrival->Pickup->Date)) ? $EstimatedArrival->Pickup->Date : "";
            return (int)human_time_diff(strtotime($Pickup), strtotime($Arrival));
        }

        public function ups_enable_disable_ups_ground($result)
        {


            $transit_day_type = get_option('restrict_calendar_transit_small_packages_ups'); //get value of check box to see which one is checked
            $days_to_restrict = get_option('restrict_days_transit_package_ups_small');
            $action = get_option("ups_small_package");
            $package = $transit_days = apply_filters('ups_small_quotes_plans_suscription_and_features', 'transit_days');
            $package = (isset($package) && ($package == 1 || $package == 2)) ? TRUE : FALSE;

            $ServiceSummary = $result->ups_rate->tnt->TransitResponse->ServiceSummary;

            $ServiceSummary = isset($ServiceSummary) && !empty($ServiceSummary) ? $ServiceSummary : array();
            if ($package && strlen($days_to_restrict) > 0 && strlen($transit_day_type) > 0) {
                foreach ($ServiceSummary as $key => $service) {
                    if (isset($service->Service->Code) && $service->Service->Code == "GND") {
                        $estimated_arrival_days = (isset($service->EstimatedArrival->$transit_day_type)) ? $service->EstimatedArrival->$transit_day_type : $this->estimated_arrival_days($service->EstimatedArrival);
                        if ($estimated_arrival_days >= $days_to_restrict) {
                            $ups_services = (array)$result->ups_services;
                            $ups_services = array_flip($ups_services);
                            $index = (isset($ups_services['UPS Ground'])) ? $ups_services['UPS Ground'] : "";

                            unset($result->ups_rate->$index);
                        }
                    }
                }
            }
            return $result;
        }

        /**
         *
         * @param array type $result
         * @return json_encode type
         */
        public function wwe_small_enable_disable_ups_ground($result)
        {

            $transit_day_type = get_option('restrict_calendar_transit_wwe_small_packages');
            $response = (isset($result->q)) ? $result->q : array();
            $days_to_restrict = get_option('ground_transit_wwe_small_packages');
            $action = get_option("wwe_small_packages_quotes_package");
            $package = (isset($action) && ($action == 1 || $action == 2)) ? TRUE : FALSE;

            if ($package && strlen($days_to_restrict) > 0 && strlen($transit_day_type) > 0) {
                foreach ($response as $row => $service) {
                    if ($service->serviceCode == "GND" &&
                        (isset($service->$transit_day_type)) &&
                        ($service->$transit_day_type >= $days_to_restrict))

                        unset($result->q[$row]);
                }

            }

            return json_encode($result);
        }
    }
}
        

