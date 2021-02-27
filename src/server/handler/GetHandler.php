<?php

namespace POICA\handler {

    use POICA\model\Logger;

    abstract class GetHandler extends Handler
    {
        public function __construct(Logger $logger, array $data = [])
        {
            parent::__construct($logger, $data);
            $this->logger->set_tag('get');
        }
    }
}
