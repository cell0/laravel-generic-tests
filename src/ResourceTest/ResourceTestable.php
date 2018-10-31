<?php

namespace Cell0\LGT\ResourceTest;

interface ResourceTestable
{
    public function it_passes_the_spec();
    public function its_response_has_all_attributes();
    public function its_response_has_the_expected_amount_of_attributes();
    public function its_responses_non_transformed_values_match_those_on_the_model();
    public function its_response_has_the_expected_aliased_attributes();
    public function it_does_not_respond_with_not_loaded_relations();
    public function it_does_respond_with_loaded_relations();
    public function its_response_has_the_expected_transformed_attributes();
}
