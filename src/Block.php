<?php

namespace Tendril;

use Timber\Core;
use Timber\CoreInterface;

class Block extends Core implements CoreInterface {

    public $PostClass = 'Timber\Block';
    public $TermClass = 'Block';

    public $block;

    public function __construct( $block ) {
        if( $block ) {
            $this->block = $block;
            $this->id = $this->ID = $block['id'] ?? '';
            $this->object_type = 'block';
        }
    }
    
    public function __toString() {
        return $this->block_data['name'];
    }

    public function meta( $key ) {
        if($key != '' && !empty($this->id) ) {
            return get_field( $key, $this->id );
        }
    }
}
