<template>
	<div>
        <div class="col-lg-4 push-lg-4 name-holder text-xs-center">
            <h2 :class="notShowOptions || name ? 'no-pencil' : ''" @click="editName()" v-if="editingName == false" v-text="name ? name : value.name"></h2>
            <input type="text" id="nameHolder" placeholder="Enter Title" class="small text-xs-center big marg-5" v-model="tempName" v-if="editingName && !notShowOptions" @blur="cancelSaveName" @keyup.enter="saveNewName" />
            <div class="clearfix"></div>
            <small v-if="editingName && !notShowOptions" >Press Enter to Save</small>
            <div class="clearfix"></div>
            <h4 :class="notShowOptions ? 'no-pencil' : ''" @click="editPosition()" v-if="!editingPosition" v-text="value.position"></h4>
            <input type="text" id="positionHolder" placeholder="Enter Subtitle" class="small text-xs-center big marg-5" v-model="tempPosition" v-if="editingPosition && !notShowOptions" @blur="cancelSavePosition" @keyup.enter="saveNewPosition" />
            <div class="clearfix"></div>
            <small v-if="editingPosition && !notShowOptions" >Press Enter to Save</small>
            <div class="clearfix"></div>
            <button :class="'btn ' + actionPointOptions.color + ' contact-btn waves-effect waves-light'" @click="showActionPointModal" v-text="actionPointText">
            </button>
        </div>
        <component v-if="actionPointOptions.type == 'component'" :is="actionPointOptions.component" :show="showingActionPointModal" :value="value" :slug="slug" :logged-in="loggedIn" @close="closeActionPointModal" >
        </component>
	</div>
</template>

<script>
    export default {
        props: {
            saving: {
                type: Boolean,
                default: false,
            },
            notShowOptions: {
                type: Boolean,
                default: true,
            },
            actionPointOptions: {
                type: Object,
                default: function () {
                    return {
                        type: 'component',
                        text: 'Contact',
                        color: 'black',
                        component: 'contact',
                    };
                },
            },
            value: {
                type: Object,
                required: true,
            },
            name: {
                type: String,
                default: '',
            },
            slug: {
                type: String,
                default: '',
            },
            loggedIn: {
                type: Boolean,
                default: false,
            },
            activePage: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                editingName: false,
                editingPosition: false,
                tempName: this.value.name,
                tempPosition: this.value.position,
                showingActionPointModal: false,
            };
        },

        computed: {
            actionPointText() {
                if (this.actionPointOptions.type == 'show'
                    && this.actionPointOptions.slug == this.activePage
                ) {
                    return this.actionPointOptions.back.text;
                }

                return this.actionPointOptions.text;
            },
        },

        methods: {
            editName() {
                if (!this.name && !this.notShowOptions) {
                    this.editingName = true;
                    this.$nextTick(() => {
                        document.getElementById('nameHolder').focus();
                    });
                }
            },

            editPosition() {
                if (!this.notShowOptions) {
                    this.editingPosition = true;
                    this.$nextTick(() => {
                        document.getElementById('positionHolder').focus();
                    });
                }
            },

            showActionPointModal() {
                if (this.actionPointOptions.type == 'event') {
                    this.$emit(this.actionPointOptions.event);
                } else if (this.actionPointOptions.type == 'component') {
                    this.showingActionPointModal = true;
                } else if (this.actionPointOptions.type == 'notice') {
                    $.growl.warning({ 
                        title: 'Notice',
                        message: this.actionPointOptions.notice,
                    });
                } else if (this.actionPointOptions.type == 'show') {
                    this.$emit(
                        'show',
                        { 
                            slug: this.activePage == this.actionPointOptions.slug
                                ? this.actionPointOptions.back.slug
                                : this.actionPointOptions.slug
                        }
                    );
                }
            },

            closeActionPointModal() {
                this.showingActionPointModal = false;
            },

            cancelSaveName() {
                this.editingName = false;
            },

            saveNewName() {
                this.value.name = this.tempName;
                this.editingName = false;
            },

            cancelSavePosition() {
                this.editingPosition = false;
            },

            saveNewPosition() {
                this.value.position = this.tempPosition;
                this.editingPosition = false;
            },
        },
    };
</script>