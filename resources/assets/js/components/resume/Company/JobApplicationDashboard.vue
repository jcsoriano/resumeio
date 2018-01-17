<style>
    .hover-trigger .show-on-hover {
        display: none;
    }

    .hover-trigger:hover .show-on-hover {
        display: block;
    }
</style>
<style scoped>
    .flex-row {
        margin-top:25px;
    }
    
    .no-padding {
        padding:0;
    }

    .no-padding .row .col-xs-12 {
        margin-left:5px;
    }

    .cursor-move {
        cursor: move !important;
    }
</style>
<template>
    <div>
        <div v-if="Object.keys(value).length">
            <div v-if="value.stages" class="flex-row">
                <div v-for="(stage, index) in value.stages" class="col-sm-6 col-md-4">
                    <div class="card">
                        <div class="card-header" >
                            <button v-show="!stage.applicants.length" type="button" class="close" @click="removeStage(index)" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <span v-show="!stage.editing" v-text="stage.name + ' (' + stage.applicants.length + ')'" @click="editStageName(stage, index)"></span>
                            <input :id="'stage-name-' + index" v-show="stage.editing" type="text" v-model="tempStageName" @keyup.enter="saveNewStageName(stage)" @keyup.esc="cancelEditStageName()" @blur="cancelEditStageName()" ></span>
                        </div>
                        <ul class="list-group" >
                            <draggable :list="value.stages[index].applicants" :options="{group: 'jobApplications'}" style="min-height:100px;">
                                <li v-for="applicant in stage.applicants" :key="applicant.user_id" class="list-group-item cursor-move hover-trigger">
                                    <div class="row">
                                        <div class="col-xs-3 no-padding">
                                            <magis-img :src="photo(applicant.user_id)" :aspect-ratio="aspectRatio(applicant.user_id)"></magis-img>
                                        </div>
                                        <div class="col-xs-9 no-padding">
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <span v-text="user(applicant.user_id).name"></span>
                                                </div>
                                            </div>
                                            <div class="row show-on-hover">
                                                <div class="col-xs-12">
                                                    <a :href="url(applicant.resumeSlug)" class="btn btn-sm btn-default" target="_blank" data-toggle="tooltip" title="View Resume"><span class="fa fa-id-card-o"></span></a>
                                                    <a href="#" class="btn btn-sm btn-default" @click="composeMessage(applicant)" data-toggle="tooltip" title="Message"><span class="fa fa-comment"></span></a>
                                                    <a href="#" class="btn btn-sm btn-default" @click="composeRemark(applicant)" data-toggle="tooltip" title="Remarks"><span class="fa fa-pencil-square-o"></span></a>
                                                    <span v-if="value.jobQuestionnaireAnswers && Object.keys(value.jobQuestionnaireAnswers).length > 0">
                                                        <a v-if="value.jobQuestionnaireAnswers[applicant.user_id]" :href="url(value.companySlug + '/' + value.jobPostingSlug +  '/' + applicant.user_id)" target="_blank" class="btn btn-sm btn-default" data-toggle="tooltip" title="Interview"><span class="fa fa-paperclip"></span></a>
                                                        <small v-else style="color: red;">Not yet answered</small>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xs-12">
                                                    <small><star-rating v-model="applicant.rating" :star-size="20" :increment="0.5"></star-rating></small>
                                                </div>
                                            </div>
                                            <div v-if="applicant.remarks" class="row">
                                                <div class="col-xs-12">
                                                    <small v-text="applicant.remarks"></small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </draggable>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <input type="text" v-model="newStage" placeholder="Create new stage" @keyup.enter="addStage" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else>
            <br/>
            <br/>
            <center>
                <p>There are no applicants for your job yet. Get lots of applicants by sharing your job opening to everyone!</p>
                <div class="addthis_inline_share_toolbox"></div>
            </center>
        </div>
        <contact :value="message.value" :slug="message.slug" :show="message.show" @close="closeMessage" logged-in reset-on-reshow></contact>
        <modal id="composeRemark" @close="closeRemark" :show="remark.show">
            <h5 slot="modal-title" class="modal-title">Remarks for <span v-text="remark.applicant.name"></span></h5>
            <div slot="modal-body" class="modal-body">
                <div class="md-form">
                    <textarea id="composeRemarkTextarea" class="md-textarea" v-model="remark.value"></textarea>
                    <label for="composeRemarkTextarea">Message</label>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" @click="closeRemark">Close</button>
                <button type="button" class="btn btn-sm btn-primary" @click="saveRemark(remark.applicant)">Save</button>
            </div>
        </modal>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Object | Array,
                default() {
                    return {};
                },
            },
        },

        beforeMount() {
            this.resetMessage();
            this.resetRemark();
        },

        data() {
            return {
                defaultProfile: url('img/user.png'),
                defaultBanner: url('img/default.jpg'),
                newStage: '',
                tempStageName: '',
                message: {},
                remark: {},
            };
        },

        mounted() {
            // do nothing if the dashboard doesn't exist yet
            if (Object.keys(this.value).length) {
                // handle initial state
                if (!this.value.stages) {
                    this.$set(this.value, 'stages', [
                        {
                            name: 'Job Applicants',
                            applicants: this.value.jobApplications ? this.value.jobApplications : [],
                        },
                        {
                            name: 'For Interview',
                            applicants: [],
                        },
                    ]);
                }

                const noStageApplicants = _.differenceBy(this.value.jobApplications, this.allApplicants, 'resumeSlug');
                this.value.stages[0].applicants = this.value.stages[0].applicants.concat(noStageApplicants);

                // reactively-initialize editing property of stage
                for (const i in this.value.stages) {
                    this.$set(this.value.stages[i], 'editing', false);
                }
            }

            this.$nextTick(() => {
                $('[data-toggle="tooltip"]').tooltip(); 
            });
        },

        computed: {
            allApplicants() {
                let allApplicants = [];

                for (const i in this.value.stages) {
                    allApplicants = allApplicants.concat(this.value.stages[i].applicants);
                }

                return allApplicants;
            },
        },

        methods: {
            resetMessage() {
                this.message = {
                    show: false,
                    value: {
                        name: '',
                    },
                    slug: '',
                };
            },

            resetRemark() {
                this.remark = {
                    show: false,
                    value: '',
                    applicant: {
                        name: '',
                    },
                };
            },

            jobUrl(slug) {
                return url(this.value.companySlug + '/' + slug);
            },

            user(userId) {
                return this.value.users[userId];
            },

            photo(userId) {
                const user = this.user(userId);
                return user.photo ? user.photo : this.defaultProfile;
            },

            aspectRatio(userId) {
                const user = this.user(userId);
                return user.aspect_ratio ? user.aspect_ratio : 1;
            },

            addStage() {
                this.value.stages.push({
                    name: this.newStage,
                    applicants: [],
                });
                this.newStage = '';
            },

            editStageName(stage, index) {
                this.cancelEditStageName();
                this.tempStageName = stage.name;
                stage.editing = true;
                this.$nextTick(() => {
                    document.getElementById('stage-name-' + index).focus();
                });
            },

            saveNewStageName(stage) {
                stage.name = this.tempStageName;
                stage.editing = false;
            },

            cancelEditStageName() {
                for (const i in this.value.stages) {
                    this.value.stages[i].editing = false;
                }
            },

            removeStage(index) {
                if (!this.value.stages[index].applicants.length) {
                    this.value.stages.splice(index, 1);
                }
            },

            url(path) {
                return url(path);
            },

            composeMessage(applicant) {
                this.message.show = true;
                this.message.value.name = this.user(applicant.user_id).name;
                this.message.slug = applicant.resumeSlug;
            },

            closeMessage() {
                this.resetMessage();
            },

            composeRemark(applicant) {
                this.remark.show = true;
                this.remark.value = applicant.remarks;
                this.remark.applicant = applicant;
            },

            closeRemark() {
                this.resetRemark();
            },

            saveRemark(applicant) {
                applicant.remarks = this.remark.value;
                this.resetRemark();
            },
        },
    };
</script>