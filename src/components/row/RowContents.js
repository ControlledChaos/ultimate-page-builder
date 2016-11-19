import Vue, { util } from 'vue'

import store from '../../store'

import Sortable from '../../plugins/vue-sortable'

import extend from 'extend'

import {sprintf} from 'sprintf-js'

// import math from 'mathjs';

// import RowList from '../row/RowList.vue';

Vue.use(Sortable);

// Row List
//Vue.component('row-list', RowList);

export default {
    name  : 'row-contents',
    props : ['index', 'model'],

    data(){
        return {

            showChild      : false,
            childId        : null,
            childComponent : '',

            l10n : store.l10n,
            grid : store.grid,

            breadcrumb           : store.breadcrumb,
            showHelp             : false,
            showSearch           : false,
            sortable             : {
                handle      : '> .tools > .handle',
                placeholder : "upb-sort-placeholder",
                axis        : 'y'
            },
            searchQuery          : '',
            selectedColumnLayout : {},
            showManualInput      : {},
            //showRatioSuggestion    : false,
            //ratioSuggestionMessage : '',
            devices              : []
        }
    },

    computed : {

        layoutOfTitle(){
            return sprintf(this.l10n.column_layout_of, this.model.attributes.title);
        },

        panelClass(){
            return [`upb-${this.model.tag}-panel`, `upb-panel-wrapper`].join(' ');
        },

    },

    updated(){
        // console.log('updated');
    },
    created(){

        this.devices = this.getDevices();
        this.setToolsForDevices();
        this.setSelectedColumnLayout();
        this.onSelectedColumnLayoutChange();

    },

    /*watch : {
     selectedColumnLayout(newValue){

     console.log(newValue);

     console.log(this.devices);

     this.showRatioSuggestion = false;
     this.validateColumnInput(newValue);
     }
     },*/


    watch: {
        devices: {
            handler: function (val, oldVal) {
                console.log(val, oldVal);
            },
            deep: true
        }
    },

    methods : {

        onSelectedColumnLayoutChange(){

            let activeDevices = this.devices.filter((d)=> !!d.active);

            console.log(activeDevices);

            this.$watch(`selectedColumnLayout`, (value) => {

                //let activeDevices = this.devices.filter((d)=> !!d.active);



                let totalColumns = activeDevices.map((device)=> value[device.id]).join(' + ').split('+');

                //console.log('Active devices', activeDevices);
                //console.log('total Columns', totalColumns);

                let shouldAddNewColumn = Math.round(totalColumns.length / activeDevices.length) > this.model.contents.length;
                let shouldRemoveColumn = Math.round(totalColumns.length / activeDevices.length) < this.model.contents.length;

                if (totalColumns.length == 1) {
                   // this.columnOperation(shouldAddNewColumn, shouldRemoveColumn);
                }

                activeDevices.map((device)=> {

                    //console.log(device.id, value[device.id]);

                    let columns = value[device.id].split('+');

                    // if( totalColumns.length >  )

                    console.log(device.id, columns.length)
                    //console.log(totalColumns.length)

                    columns.map((col, i)=> {

                        //colArray.push({`${device.id}`:col.trim()});
                        if (this.model.contents[i]) {
                            this.model.contents[i].attributes[device.id] = col.trim()
                        }

                        //console.log(i, `${device.id}`, col.trim())

                    });

                    //return value[device.id]

                });

            }, {deep : true});

            // device specific watch


            //this.$watch(`selectedColumnLayout`)
        },

        columnOperation(add, remove, device){

           // extend(true, {}, this.model._upb_options.tools.contents.new)
        },

        getDevices(){
            let grid       = extend(true, {}, this.grid);
            let hasCurrent = false;
            let lastIndex  = 0;

            let devices = grid.devices.map((device, index) => {

                device['active']  = false;
                device['current'] = false;

                this.model.contents.map((column) => {
                    let selected = column.attributes[device.id].trim();

                    if (selected) {
                        device.active = true;
                        lastIndex     = index;
                    }

                });

                device.current = (device.active && device.id == grid.defaultDeviceId) ? true : false;

                if (device.current) {
                    hasCurrent = true;
                }

                return device;
            });

            if (!hasCurrent) {
                devices[lastIndex].current = true;
            }

            return devices;
        },

        setToolsForDevices(){

            this.devices.map((device) => {
                this.model._upb_options.tools[device.id] = extend(true, [], this.model._upb_options.tools.contents.layouts);
            });

        },

        toggleDevice(device){
            device.active = !device.active;
        },

        currentDevice(device){

            this.devices.map((d) => {
                d.current = false;
            });

            device.current = true;
        },

        validateColumnInput(newValue){
            try {

                let totalGrid = newValue.split('+').map((i) => i.trim());

                let gridArray = totalGrid.map((i) => parseInt(i.split(':')[0].trim()));

                let gridValueCount = totalGrid.reduce((old, i) => {
                    let col = i.split(':')[0].trim();
                    return old + parseInt(col);
                }, 0);

                let totalGridValue = totalGrid.reduce((old, i)=> {
                    let ratio = i.split(':')[1].trim();
                    return old + parseInt(ratio);
                }, 0);

                let grid = totalGridValue / totalGrid.length;

                if (grid == gridValueCount) {

                    // suggession msg

                    this.ratioSuggestion(grid, gridArray, gridValueCount);

                }
                else {
                    // errors
                }

                //console.log(ratio);
                //console.log(items);

            } catch (e) {

            }
        },

        ratioSuggestion(grid, gridArray, gridValueCount){
            // I know that ES6 have spread operator but my IDE in .vue extension does not support
            // es6 features :(
            // math.gcd(...itemArray)

            //  A ratio can be simplified by dividing both sides of the ratio by the Highest Common Factor (HCF). I mean greatest common divisor :D

            let itemArray = gridArray.slice(0, gridArray.length);

            itemArray.push(gridValueCount);

            // console.log(itemArray);

            let common = math.gcd(...itemArray);
            if (common > 1) {

                itemArray.pop();

                let simplifiedRatio = gridValueCount / common;

                let simplifiedGrid = itemArray.map((i) => (i / common) + ":" + simplifiedRatio);

                this.ratioSuggestionMessage = sprintf(this.grid.simplifiedRatio, simplifiedGrid.join(' + '));

                this.showRatioSuggestion = true;
            }
        },

        openManualInput(deviceId){
            this.showManualInput[deviceId] = !this.showManualInput[deviceId];
        },

        columnLayouts(device){
            return this.model._upb_options.tools[device.id];
        },

        setSelectedColumnLayout(){

            //console.log(this.model.contents);

            this.devices.map((device) => {

                Vue.set(this.selectedColumnLayout, device.id, '');
                Vue.set(this.showManualInput, device.id, false);

                let selected = this.model.contents.map((column) => {

                    if (column.attributes[device.id].trim()) {
                        return column.attributes[device.id].trim();
                    }
                    return false;
                });

                Vue.set(this.selectedColumnLayout, device.id, _.compact(selected).join(' + '));

            });
        },

        changeColumnLayout(layout, deviceId){
            this.selectedColumnLayout[deviceId] = layout.value.trim();
        },

        miniColumns(columns){
            return columns.split('+').map((i) => i.trim());
        },

        miniColumnItem(item){
            return item.split(':')[0].trim();
        },

        miniColumnItemClass(item){
            let i = item.split(':')[0].trim();
            return `grid-item-${i}`;
        },

        columnLayoutClass(layout, deviceId){

            //console.log(layout.value, this.selectedColumnLayout[deviceId]);

            return [
                (layout.value == this.selectedColumnLayout[deviceId]) ? 'active' : '',
                layout.class
            ].join(' ');
        },

        loadContents(){
            if (this.model.contents.length > 0) {
                this.$progressbar.show();
                store.upbElementOptions(this.model.contents, (data) => {

                    this.$nextTick(function () {
                        Vue.set(this.model, 'contents', extend(true, [], data));
                        this.afterContentLoaded();
                    });

                    this.$progressbar.hide();
                }, function (data) {
                    console.log(data);
                    this.$progressbar.hide();
                });
            }
        },

        afterContentLoaded(){
            if (this.model.contents.length > 0) {
                this.childId = 0;
            }
        },

        showSettingsPanel(){
            this.$emit('showSettingsPanel')
        },

        back(){
            this.$emit('onBack')
        },

        backed(){
            this.breadcrumb.pop();
            this.showChild      = false;
            this.childId        = null;
            this.childComponent = '';
        },

        clearPanel(){
            this.breadcrumb.pop();
            this.childComponent = '';
            //this.showChild      = false;
        },

        openContentsPanel(index){

            this.clearPanel();

            //this.showChild      = true;
            this.childId        = index;
            //this.rowId          = index;
            this.childComponent = 'row-contents-panel';
            // this.breadcrumb.push(this.model.title);
        },

        openSettingsPanel(index){

            this.clearPanel();

            this.showChild      = true;
            this.childId        = index;
            this.childComponent = 'row-settings-panel';
            this.breadcrumb.push(this.model.title);
        },

        singleModel(){
            return this.model.contents[this.childId];
        },

        listPanel(id){
            return `${id}-list`;
        },

        deleteItem(index){
            this.model.contents.splice(index, 1);
            store.stateChanged();
        },

        cloneItem(index, item){
            let cloned              = extend(true, {}, item);
            cloned.attributes.title = `Clone of ${cloned.attributes.title}`;
            this.model.contents.splice(index + 1, 0, cloned);
            store.stateChanged();
        },

        onUpdate(e, values){


            //###
            //this.contents.splice(values.newIndex, 0, this.contents.splice(values.oldIndex, 1).pop());
            store.stateChanged();

            //### If you Need to modify this.model.contents then you should use this code :)
            let list = extend(true, [], this.model.contents);

            list.splice(values.newIndex, 0, list.splice(values.oldIndex, 1).pop());

            Vue.delete(this.model, 'contents');

            this.$nextTick(function () {
                Vue.set(this.model, 'contents', extend(true, [], list));
            });

            // store.stateChanged();
        },

        onStart(e){
            this.searchQuery = ''
        },

        toggleHelp(){
            this.showSearch = false;
            this.showHelp   = !this.showHelp;
        },

        toggleFilter(){
            this.showHelp   = false;
            this.showSearch = !this.showSearch;

            this.$nextTick(function () {
                if (this.showSearch) {
                    this.$el.querySelector('input[type="search"]').focus()
                }
            });
        },

        callToolsAction(event, action, tool){

            let data = tool.data ? tool.data : false;

            if (!this[action]) {
                util.warn(`You need to implement ${action} method.`, this);
            }
            else {
                this[action](event, data);
            }
        },

        addNew(e, data){
            let section = extend(true, {}, data);

            section.attributes.title = sprintf(section.attributes.title, (this.model.contents.length + 1));

            this.model.contents.push(section);
            store.stateChanged();
        }
    }
}

