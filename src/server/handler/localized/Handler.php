<?php

namespace POICA\handler\localized {

    use POICA\handler\Handler as Base;
    use POICA\model\Localizer;
    use POICA\model\Logger;

    abstract class Handler extends Base
    {
        /**
         * Instantiate a new Handler object.
         *
         * @param Logger $logger Logger for handler.
         * @param array $data Data used in handling process. If null supplied, it will absorb from php://input; if content type was set to application/json, it will parse into array, else it will set to $data as it. And process with localizer after set.
         */
        public function __construct(Logger $logger, array $data = null)
        {
            parent::__construct($logger, $data);

            if (!empty($this->data)) {
                if (is_string($this->data)) {
                    $this->data = Localizer::localize_string($this->data);
                } elseif (is_array($this->data)) {
                    $this->data = Localizer::localize_array($this->data);
                }
            }
        }
    }
}
