<?php defined( 'ABSPATH' ) or die( 'Keep Silent' ); ?>

<div v-show="isEnabled" :id="addID()" :class="addClass()" v-preview-element>
    <a href="#" :class="{ active: model.attributes.active, 'upb-accordion-item': true }" v-text="model.attributes.title"></a>
    <div :class="{ active: model.attributes.active, 'upb-accordion-content': true }">
        <div v-html="model.contents"></div>
    </div>
</div>