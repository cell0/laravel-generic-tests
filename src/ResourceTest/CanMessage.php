<?php
/**
 * Created by PhpStorm.
 * User: diedegulpers
 * Date: 31/10/2018
 * Time: 17:15
 */

namespace Cell0\LGT\ResourceTest;


trait CanMessage
{
    /**
     * adds a pretty jsons version of our resource response to the error message.
     *
     * @param $message
     * @return string
     */
    private function message($message)
    {
        return $message . " \n" . $this->prettyJson($this->resourceResponse);
    }

    /**
     * wrapper function to json encode input to the JSON PRETTY PRINT format.
     *
     * @param $input
     * @return string
     */
    private function prettyJson($input)
    {
        return json_encode($input, JSON_PRETTY_PRINT);
    }

    /**
     * builds up and returns the error message that is given when the amount of expected attributes differs from the
     * one found.
     *
     * @return string
     */
    private function unexpected_attribute_amount()
    {
        $responseKeys = array_keys($this->resourceResponse);
        $expectedAttributes = array_merge($this->attributes, array_keys($this->aliases), $this->transformations);
        $moreKeysThenExpected = array_diff($responseKeys, $expectedAttributes);
        $lessKeysThenExpected = array_diff($expectedAttributes, $responseKeys);

        if (count($moreKeysThenExpected)) {
            return "the resource's response has unexpected attributes: \n" .
                "- the response shows these attributes not shown in your attributes list: \n" .
                $this->prettyJson($moreKeysThenExpected);
        }
        return "the resource's response is missing unexpected attributes: \n" .
            "-the attributes shows these attributes not shown in your response: \n" .
            $this->prettyJson($lessKeysThenExpected);
    }
}
