<template>
    <div class="print-half">
        <div :class="align == 'left' ? 'col-lg-4 col-md-6 pull-lg-4 side-text '+textAlign : 'col-lg-4 col-md-6 side-text '+textAlign">
            <h5 v-if="(!editingTitle && value.items.length != 0) || (!notShowOptions && !editingTitle)" v-text="value.title" @click="editNewTitle" :class="!notShowOptions ? 'with-pen-p text-xs-center'+ textAlign : 'text-xs-center'+ textAlign"></h5>
            <input type="text" id="expTitle" class="small text-xs-center" v-if="editingTitle && !notShowOptions" v-model="tempTitle" placeholder="Enter Title" @blur="cancelSaveTitle" @keyup.enter="saveNewTitle">
            <draggable :list="value.items" element="p" :options="{group:{name: 'snapshots', put: false, pull: false}, animation: 100, disabled: disableDrag}">
                <snapshot-items :notShowOptions="notShowOptions" :index="index" @removeItem="removeItem(index, value.items)" :value="bio" v-for="(bio, index) in value.items"></snapshot-items>
            </draggable>
            <div class="text-xs-center button-holder">
                <button class="btn btn-primary btn-sm" v-if="!notShowOptions" @click="addBio()"><i class="fa fa-plus left inherit-size"></i>Add <span v-text="value.title"></span></button>
            </div>             
        </div>
    </div>
</template>

<script>
    export default {
        // props: ['value','notShowOptions']
        props: {
            notShowOptions: {
                required: true,
                default: false,
            },
            textAlign: {
                required: false,
                default: "text-lg-left",
            },
            align: {
                required: true,
                type: String,
            },
            value: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                editingTitle: false,
                tempTitle: this.value.title,
                disableDrag: false,
            };
        },

        beforeMount() {
            this.value.items = this.value.items.map(val => {
                val.saved = true;
                return val;
            });
        },

        computed: {
            disableDrag() {
                return this.value.items.filter(exp => exp.editing).length > 0
                || this.notShowOptions;
            },
        },

        methods: {
            removeItem(index, dataType) {
                if (!this.notShowOptions) {
                    dataType.splice(index, 1);
                }
            },

            addBio() {
                var exp = {
                    title: '',
                    description: '',
                    editing: true,
                    saved: false,
                };
                this.value.items.push(exp);
                this.disableDrag = true;
            },

            editNewTitle() {
                if (!this.notShowOptions) {
                    this.editingTitle = true;
                    this.$nextTick(function () {
                        document.getElementById('expTitle').focus();
                    });
                }
            },

            saveNewTitle() {
                if (this.tempTitle != '') {
                    this.value.title = this.tempTitle;
                    this.editingTitle = false;
                } else {
                    toastr.warning('Add Title');
                }
            },

            cancelSaveTitle() {
                this.editingTitle = false;
            },
        },
    };
</script>