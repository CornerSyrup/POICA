<?php

namespace POICA\handler\localized {

    use POICA\handler\DeleteHandler as Handler;
    use POICA\model\Localizer;
    use POICA\model\Logger;

    abstract class DeleteHandler extends Handler
    {
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
