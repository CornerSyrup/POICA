<?php

namespace POICA\handler {

    use POICA\model\Logger;

    abstract class DeleteHandler extends Handler
    {
        public function __construct(Logger $logger, array $data = null)
        {
            parent::__construct($logger, $data);
            $this->logger->set_tag('del');
        }
    }
}
