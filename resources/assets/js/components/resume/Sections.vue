<template>
    <div>
        <div class="col-lg-10 offset-lg-1 step-holder" v-if="value.length != 0">
            <draggable :list="value" class="stepper stepper-horizontal" element="ul" :options="{group:{name: 'circles', put: false, pull: false}, animation: 100, chosenClass: 'active', disabled: disableDrag}">
                <li v-for="(op, opindex) in value">
                    <a @click="scrollToDiv(op.type, opindex)">
                        <span class="circle"></span><br>
                        <span class="under-word" v-text="op.name"></span>
                    </a>
                </li>
            </draggable>
            <!--Navbar-->
            <div class="row">
                <nav class="navbar navbar-toggleable-md navbar-light hidden-nav">
                    <center>
                        <button class="navbar-toggler navbar-toggler-right float-none z-depth-1" type="button" data-toggle="collapse" data-target="#navbarNav1" aria-controls="navbarNav1" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="fa fa-bars"></span>
                        </button>
                    </center>
                    <div class="container">
                        <div class="collapse navbar-collapse" id="navbarNav1">
                            <ul class="navbar-nav mr-auto">
                                <li v-for="(op, opindex) in value" class="nav-item" @click="scrollToDiv(op.type, opindex)">
                                    <a class="nav-link">
                                        <span v-text="op.name"></span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
        <div class="no-break" v-for="(section, sectionIndex) in value">
            <div class="clearfix"></div>
            <div class="spacer-50 none-print"></div>
            <div class="clearfix"></div>
            <div class="col-lg-1 col-md-1 np-right text-lg-left text-xs-center none-print" :id="'xs-'+section.type+'-'+sectionIndex">
                <img :src="section.image" class="img-fluid">
                <button class="btn btn-primary smallbtns add-marg-top" v-text="section.image == '' ? 'Upload Image' : 'Change Image'" v-if="!notShowOptions" @click="openImageChanger(sectionIndex)"></button>
            </div><!--
        --><div class="col-lg-11 col-md-11 p-left-30 big-holder" :id="section.type+'-'+sectionIndex">
                <h2 class="print-left text-lg-left text-md-left text-xs-center pos-rel">
                    <span v-text="section.name" @click="editOptionName(section, sectionIndex)" v-if="!section.editName"></span>
                    <input v-if="section.editName" type="text" class="small big width-60" v-model="section.name" :id="'input-' + section.type + '-' + sectionIndex" placeholder="Enter Title" @keyup.enter="saveName(section)" @blur="saveName(section)">
                    <span class="tag tag-danger small-remove" v-if="!notShowOptions" @click="openModal(section.type, sectionIndex)"><i class="fa fa-remove"></i></span>
                </h2>
                <hr class="big-hr">
                <div class="print-left-pad">
                    <component :is="section.type" :not-show-options="notShowOptions" :value="section" v-if="section.type != 'paragraph-section'" :index="sectionIndex" :path="path"> 
                    </component>
                    <paragraph-section :id="section.type+'-'+sectionIndex" :value="section" :not-show-options="notShowOptions" v-if="section.type == 'paragraph-section'" @dragYes="dragResult(section, false)" @dragNo="dragResult(section, true)"></paragraph-section>
                </div>
                <div class="clearfix"></div>
            </div>            
            <div class="modal fade" :id="'confirmDelete-'+section.type+'-'+sectionIndex" tabindex="-1" role="dialog" :aria-labelledby="'confirmDelete-'+section.type+'-'+sectionIndex" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <!--Content-->
                    <div class="modal-content">
                        <!--Header-->
                        <div class="modal-header" style="border-top: none;">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer" style="border-top: none; margin-top: 0px; text-align: center;">
                            <h5 style="margin-bottom: 30px;">Are you sure you want to delete <span v-text="section.name"></span>?</h5>
                            <button type="button" class="btn btn-primary btn-sm"  @click="removeOption(section, sectionIndex)">Yes</button>
                            <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">No</button>
                        </div>
                    </div>
                    <!--/.Content-->
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="spacer-50" v-if="value.length > 0"></div>
        <div class="col-lg-4 offset-lg-4 select-holder" v-if="!notShowOptions">
            <label>Add new Section</label>
            <select class="browser-default form-control section-chooser" id="optionProfile" v-model="chosenSection" :disabled="this.disableOtherButtonExceptSave">
                <option value="default">Choose your option</option>
                <option :value="index" v-for="(secType, index) in sectionTypes" v-text="secType.name"></option>
            </select>
        </div>
        <!-- <gallery-section></gallery-section> -->
        <div class="clearfix"></div>


        <!--Modals here-->
        <div class="modal fade" :id="iconUploadId" tabindex="-1" role="dialog" aria-labelledby="iconUploadLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <!--Content-->
                <div class="modal-content">
                    <!--Header-->
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="iconUploadLabel">Upload Icon</h5>
                    </div>
                    <!--Body-->
                    <div class="modal-body">
                        <!-- <div class="md-form">
                            <input type="text" :id="iconUploadInputId" v-model="tempPicture" class="form-control">
                            <label :for="iconUploadInputId" class="">Enter Link</label>
                        </div> -->
                        <magis-photo v-model="tempPicture" :path="path" no-gallery></magis-photo>
                    </div>
                    <!--Footer-->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" @click="cancelImageChanger()">Close</button>
                        <button type="button" class="btn btn-primary btn-sm" @click="saveImageChanger()">Save</button>
                    </div>
                </div>
                <!--/.Content-->
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        // props: ['value','notShowOptions']
        props: {
            notShowOptions: {
                type: Boolean,
                default: false,
            },
            questionnaire: {
                type: Boolean,
                default: false,
            },
            idModifier: {
                type: String,
                default: 'section',
            },
            value: {
                type: Array,
                required: true,
            },
            path: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                chosenSection: 'default',
                tempPicture: '',
                tempIndex: '',
                sectionTypes: [
                    {
                        type: 'bullet-section',
                        name: 'Bullet with Date Range Section',
                        editName: false,
                        image: url('img/work.png'),
                        withDateRange: true,
                        data: [ ],
                        bulletName: 'Bullet Point',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Bullet Section',
                        editName: false,
                        image: url('img/awards.png'),
                        editName: false,
                        data: [ ],
                        bulletName: 'Bullet Point',
                        disableDrag: false,
                    },
                    {
                        type: 'rating-section',
                        name: 'Rating Section',
                        editName: false,
                        image: url('img/skills.png'),
                        data: [ ],
                        bulletName: 'Rating',
                        disableDrag: false,
                    },
                    {
                        type: 'paragraph-section',
                        name: 'Paragraph Section',
                        editName: false,
                        image: '',
                        description: {
                            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse finibus, turpis nec interdum condimentum, arcu enim dignissim augue, a ultricies ligula lacus quis massa. Morbi sit amet imperdiet magna, eu consectetur erat. Vivamus varius, enim eget fringilla tristique, diam erat consequat odio, id iaculis ligula augue sed metus. In et auctor nisl. Fusce cursus, sem ut luctus cursus, elit mi semper risus, nec aliquet neque ligula nec elit.',
                            saved: true,
                            editMode: false,
                        },
                        bulletName: 'Paragraph',
                        disableDrag: false,
                    },
                    {
                        type: 'gallery-section',
                        name: 'Gallery',
                        editName: false,
                        image: url('img/camera.png'),
                        data: [ ],
                        bulletName: 'Gallery Item',
                        disableDrag: false,
                    },
                    {
                        type: 'link-section',
                        name: 'Links',
                        editName: false,
                        image: url('img/link.png'),
                        data: [ ],
                        bulletName: 'Link',
                        disableDrag: false,
                    },
                ],
            };
        },

        mounted() {
            if (this.questionnaire) {
                const questionSectionTypes = [
                    {
                        type: 'paragraph-section',
                        name: 'Essay Question',
                        editName: true,
                        image: url('img/about.png'),
                        description: {
                            text: '',
                            saved: true,
                            editMode: false,
                        },
                        question: true,
                        disableDrag: true,
                    },
                    {
                        type: 'rating-section',
                        name: 'Rating Questions',
                        editName: true,
                        image: '',
                        data: [],
                        question: true,
                        bulletName: 'Rating Question',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Form Questions',
                        editName: false,
                        image: url('img/work.png'),
                        withDateRange: true,
                        data: [],
                        question: true,
                        bulletName: 'Question',
                        disableDrag: false,
                    },
                ];
                this.sectionTypes = questionSectionTypes.concat(this.sectionTypes);
            }
        },

        computed: {
            disableDrag() {
                return this.value.filter(exp => exp.disableDrag).length > 0
                || this.notShowOptions;
            },

            iconUploadId() {
                return this.idModifier + '-iconUpload';
            },

            iconUploadInputId() {
                return this.idModifier + '-iconUploadInput';
            },
        },

        watch: {
            chosenSection(newVal) {
                if (newVal != 'default' && !this.notShowOptions) {
                    const select = this.chosenSection;
                    const newSection = this.sectionTypes[select];
                    this.value.push(JSON.parse(JSON.stringify(newSection)));
                    this.$nextTick(() => {
                        if (newSection.editName) {
                            this.focusOnSectionName(newSection.type, this.value.length - 1, true);
                        }
                        this.chosenSection = 'default';
                    });
                }
            },
        },

        methods: {
            removeItem: function (opdata, index) {
                opdata.splice(index, 1);
            },

            addNewItem: function (option) {
                var toPush;
                toPush = {
                    editMode: true,
                    dateTo: '',
                    dateFrom: '',
                    title: '',
                    description: '',
                    saved: false,
                };

                this.$nextTick(function () {
                    option.data.push(toPush);
                });
            },

            removeOption: function (option, index) {
                var num = this.sectionTypes.length;
                var anotherNum;
                for (anotherNum = 0; num > anotherNum; anotherNum++) {
                    if (this.sectionTypes[anotherNum].type == option.type) {
                        this.sectionTypes[anotherNum].selected = false;
                    }
                }
                var id = '#confirmDelete-' + option.type + '-' + index;
                $(id).modal('hide');
                this.value.splice(index, 1);
            },

            focusOnSectionName(sectionType, sectionIndex, select = false) {
                this.$nextTick(() => {
                    const id = 'input-' + sectionType + '-' + sectionIndex;
                    document.getElementById(id).focus();

                    if (select) {
                        document.getElementById(id).select();
                    }
                });
                
            },

            editOptionName(option, sectionIndex) {
                if (!this.notShowOptions) {
                    option.editName = true;
                    this.focusOnSectionName(option.type, sectionIndex);
                }
            },

            saveName(option) {
                option.editName = false;
            },

            openImageChanger(index) {
                this.tempIndex = index;
                $('#' + this.iconUploadId).modal();
                $('#' + this.iconUploadId).on('shown.bs.modal', function (e) {
                     // document.getElementById(this.iconUploadInputId).focus();
                });
            },

            saveImageChanger() {
                this.value[this.tempIndex].image = this.tempPicture;
                this.tempPicture = '';
                $('#' + this.iconUploadId).modal('hide');
            },

            cancelImageChanger() {
                this.tempPicture = '';
                $('#' + this.iconUploadId).modal('hide');
            },

            dragResult(section, bool) {
                section.disableDrag = bool;
            },

            scrollToDiv(opType, opindex) {
                // const id = opType + '-' + opindex;
                // this.$SmoothScroll(document.getElementById(id));
                const id = '#' + opType + '-' + opindex;
                const id2 = '#xs-' + opType + '-' + opindex;
                // if($(window).width() < 768){
                //     $('#navbarNav1').collapse('hide');
                // }
                this.$nextTick(() => {
                    if($(window).width() > 767){
                        const $body = $('body');
                        const $target = $(id);
                        $body.stop().animate({ scrollTop: $target.offset().top - 15 }, '500', 'swing');
                    }else{
                        const $body = $('body');
                        const $target = $(id2);
                        $body.stop().animate({ scrollTop: $target.offset().top - 15 }, '500', 'swing');                        
                    }
                });
            },

            openModal(secType, index) {
                var id = '#confirmDelete-' + secType + '-' + index;
                this.$nextTick(() => {
                    $(id).modal();
                });
            },
        },
    };
</script>