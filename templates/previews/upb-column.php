<?php defined( 'ABSPATH' ) or die( 'Keep Silent' ); ?>
<div v-preview-element v-droppable :class="addClass()">

    <upb-preview-mini-toolbar :model="model"></upb-preview-mini-toolbar>

    <component v-for="(content, index) in model.contents" v-if="isElementRegistered(content.tag)" :index="index" :model="content" :is="content._upb_options.preview.component"></component>

    <a href="#" @click.prevent="openElementsPanel()" class="upb-add-element-message" v-else v-text="model._upb_options.meta.messages.addElement"></a>

    <a href="#" @click.prevent="openElementsPanel()" class="upb-add-element-message" v-if="!hasContents" v-text="model._upb_options.meta.messages.addElement"></a>

</div>