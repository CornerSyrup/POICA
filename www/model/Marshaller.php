<?php

namespace model {

    use Exception;

    /**
     * Internal marshalling service provider.
     */
    class Marshaller
    {
        const HALGO = 'sha256';

        /**
         * Marshalling data to json format for respond of request.
         *
         * @param string $data raw data.
         * @param integer $status status code.
         * @return string marshalled string in json.
         */
        public static function Marshal(string $data, int $status = 200): string
        {
            return json_encode(array(
                "data" => $data,
                "status" => $status,
                "hash" => hash(self::HALGO, $data)
            ));
        }

        /**
         * Unmarshalling data from json format, extract only "data" field.
         *
         * @param string $data raw json data.
         * @return mixed[]|mixed data from request, "data" field only.
         */
        public static function Unmarshal(string $data)
        {
            /**
             * @var array decoded array from received data.
             */
            $raw = json_decode($data, true);
            /**
             * @var mixed[]|mixed content of request from sender.
             */
            $cont = $raw['data'];
            /**
             * @var string hash of received data from sender.
             */
            $hash = $raw['hash'];

            if (hash(self::HALGO, $cont) != $hash) {
                throw new Exception('hash mismatch, req resend');
            } else {
                return $cont;
            }
        }
    }
}
