<?php

namespace POICA\handler {

    use POICA\model\Helper;
    use POICA\model\Logger;

    abstract class Handler implements IHandleable
    {
        #region fields
        /**
         * Data which will be used in handling process.
         *
         * @var array|string
         */
        protected $data;

        /**
         * Logger for handler.
         *
         * @var Logger
         */
        protected $logger;

        /**
         * Result of the handling.
         *
         * @var array
         */
        protected $result = [];
        #endregion

        /**
         * Instantiate a new Handler object.
         *
         * @param Logger $logger Logger for handler.
         * @param array $data Data used in handling process. If null supplied, it will absorb from php://input; if content type was set to application/json, it will parse into array, else it will set to $data as it.
         */
        public function __construct(Logger $logger, array $data = null)
        {
            $this->logger = $logger;

            if (is_null($data)) {
                $this->data = file_get_contents('php://input');

                if (
                    isset($_SERVER['CONTENT_TYPE'])
                    && strtolower($_SERVER['CONTENT_TYPE'] == 'application/json')
                ) {
                    $this->data = Helper::json_parse($this->data);
                }
            } else {
                $this->data = $data;
            }
        }

        #region methods
        public function get_result(): array
        {
            return empty($this->result) ? [] : $this->result;
        }
        #endregion
    }
}
