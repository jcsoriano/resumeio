<style>
    .footer-link {
        color: #333;
        padding-bottom:20px;
    }
</style>
<template>
	<div>
        <navigation-links :slug="slug" :left="leftNavigationLinks" :right="rightNavigationLinks" :not-show-options="notShowOptions" :active-page="activePage" :intent="intent" @show="showPage" @verificationRequest="showVerifyModal"></navigation-links>
        <banner-picture :not-show-options="notShowOptions" v-model="value.bannerPicture" :path="syncUrlSegment"></banner-picture>
        <profile-picture :not-show-options="notShowOptions" v-model="value.profilePicture" :path="syncUrlSegment"></profile-picture>
        <div class="container width-900">
            <div class="row">
                <mini-profile :not-show-options="this.notShowOptions" :value="value.profile" :name="name" :active-page="activePage" :action-point-options="actionPointOptions" :saving="saving" :slug="slug" @save="saveChanges" @apply="apply" @show="showPage"></mini-profile>
                <snapshot :not-show-options="notShowOptions" :value="value.leftSnapshot" align="left" text-align="text-lg-left text-md-left"></snapshot><!--
                --><snapshot :not-show-options="notShowOptions" :value="value.rightSnapshot" align="right" text-align="text-lg-right text-md-right"></snapshot>
            </div>
            <div class="print-row" v-if="activePage == 'main'">
                <sections :not-show-options="notShowOptions" :value="value.sections" :path="syncUrlSegment"></sections>
            </div>
            <div v-for="lwpc in linksWithPageComponents" v-if="activePage == lwpc.slug">
                <component :is="lwpc.component" :not-show-options="notShowOptions" :value="value[lwpc.valueProp]" :company-slug="value.slug" :path="syncUrlSegment">
                </component>
            </div>
        </div>
        <sign-up v-if="applyTo" :apply-to="applyTo" id="signup-to-apply-modal" :show="signingUpToApply" @close="closeSignupToApplyModal" @switchToLogin="switchToLoginToApply"></sign-up>
        <log-in v-if="applyTo" :apply-to="applyTo" id="login-to-apply-modal" :show="loggingInToApply" @close="closeLoginToApplyModal" @switchToSignup="switchToSignupToApply" ></log-in>
        <modal v-if="introModal" id="intro-modal" :show="showingIntroModal" @close="closeIntroModal">
            <center slot="modal-title" class="modal-title"><h5>Welcome!</h5></center>
            <div slot="modal-body" class="modal-body intro-modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <center>
                            <p v-text="introModal"></p>
                        </center>
                    </div>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <center>
                    <button type="button" class="btn btn-primary btn-sm" @click="closeIntroModal">Thanks! I understand I won't see this pop-up again. Close this now.</button>
                </center>
            </div>
        </modal>
        <modal id="saved-modal" :show="showingSavedModal" @close="closeSavedModal">
            <center slot="modal-title" class="modal-title"><h5>Your changes have been saved!</h5></center>
            <div slot="modal-body" class="modal-body intro-modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <center>
                            <p v-if="verified">Promote your 
                                <span v-if="value.type == 'company'">Company to the world and let them know about your brand!</span>
                                <span v-if="value.type == 'job_posting'">Job Opening to get lots of applicants!</span>
                                <span v-if="value.type == 'resume'">Resume to the world to gain credibility and increase opportunities!</span>
                            </p>
                            <p v-if="!verified">
                                However, please verify your email by clicking the verification link that was sent to you. <a href="#" @click="resendVerificationEmail" :disabled="resendingVerificationLinkStatus != 'none'" v-text="resendVerificationLinkText" ></a>. <span v-text="value.type == 'company' ? 'You will not be able to create jobs if your email is not verified.' : 'Companies will not be able to contact you and you will not be able to apply to jobs if your email is not verified.'"></span>
                            </p>
                        </center>
                    </div>
                </div>
                <div class="row" v-if="verified">
                    <div class="col-xs-12">
                        <div class="addthis_inline_share_toolbox"></div>
                    </div>
                </div>
            </div>
        </modal>
        <modal id="verify-modal" :show="showingVerifyModal" @close="closeVerifyModal">
            <center slot="modal-title" class="modal-title"><h5>Email Verification</h5></center>
            <div slot="modal-body" class="modal-body intro-modal-body">
                <div class="row">
                    <div class="col-xs-12">
                        <center>
                            <p>Please verify your email<span v-show="!editing"> before applying</span>. <a href="#" @click="resendVerificationEmail" :disabled="resendingVerificationLinkStatus != 'none'" v-text="resendVerificationLinkText" ></a>.</p>
                        </center>
                    </div>
                </div>
            </div>
            <div slot="modal-footer" class="modal-footer">
                <button type="button" class="btn btn-default" @click="closeVerifyModal">Close</button>
            </div>
        </modal>
    	<button v-if="editing" class="btn btn-primary btn-sm fixed-btn" @click="toShow()" v-text="notShowOptions ? 'Swicth to Edit Mode' : 'Switch to Preview Mode'"></button>
        <!-- <div class="row">
            <center>
                <small>
                    <a href="https://myresum.es" class="footer-link" target="_blank">About My Resumes</a>
                    &nbsp; | &nbsp; 
                    <a href="https://myresum.es/terms-of-use" class="footer-link" target="_blank">Terms of Use</a>
                    &nbsp; | &nbsp; 
                    <a href="https://myresum.es/privacy-policy" class="footer-link" target="_blank">Privacy Policy</a>
                </small>
            </center>
        </div> -->
	</div>
</template>

<script>
    export default {
        props: {
            leftNavigationLinks: {
                type: String | Array,
                default: [],
            },
            rightNavigationLinks: {
                type: String | Array,
                default: [],
            },
            value: {
                type: Object,
                required: true,
            },
            actionPointOptions: {
                type: Object,
            },
            editing: {
                type: Boolean,
                default: false,
            },
            initShowOptions: {
                type: Boolean,
                default: false,
            },
            syncUrl: {
                type: String,
                default: '',
            },
            syncUrlSegment: {
                type: String,
                default: '',
            },
            slug: {
                type: String,
                default: '',
            },
            jobPostings: {
                type: Array,
                default() {
                    return [];
                },
            },
            applyTo: {
                type: String,
                default: '',
            },
            introModal: {
                type: String,
                default: '',
            },
            name: {
                type: String,
                default: '',
            },
            intent: {
                type: String,
                default: '',
            },
            loggedIn: {
                type: Boolean,
                default: false,
            },
            userHome: {
                type: String,
                default: '',
            },
        },

        beforeMount() {
            this.setValueIfUndefined('bannerPicture', url('img/default.jpg'));
            this.setValueIfUndefined('profilePicture', url('img/user.png'));
        },

        data() {
            return {
                notShowOptions: !this.editing || !this.initShowOptions,
                signingUpToApply: false,
                loggingInToApply: false,
                showingIntroModal: false,
                showingSavedModal: false,
                showingVerifyModal: false,
                saving: false,
                numSaves: 1,
                applying: false,
                activePage: 'main',
                verified: true,
                resendingVerificationLinkStatus: 'none',
            };
        },

        mounted() {
            if (this.introModal) {
                this.showingIntroModal = true;
            }

            if (this.intent == 'verify') {
                this.showVerifyModal();
            }
        },

        computed: {
            allLinks() {
                return this.leftNavigationLinks.concat(this.rightNavigationLinks);
            },

            linksWithPageComponents() {
                return this.allLinks.filter(link => link.type == 'show');
            },

            currentLink() {
                return _.find(this.linksWithPageComponents, { slug: this.activePage });
            },

            syncUrlOfActivePage() {
                if (this.activePage != 'main' && this.currentLink.syncUrl) {
                    return this.currentLink.syncUrl;
                }
                return this.syncUrl;
            },

            valueOfActivePage() {
                if (this.activePage != 'main' && this.currentLink.valueProp) {
                    return this.value[this.currentLink.valueProp];
                }
                return this.value;
            },

            resendVerificationLinkText() {
                if (this.resendingVerificationLinkStatus == 'none') {
                    return 'Click here to resend the verification e-mail';
                }

                if (this.resendingVerificationLinkStatus == 'resending') {
                    return 'Re-sending...';
                }

                if (this.resendingVerificationLinkStatus == 'resent') {
                    return 'Verification link re-sent!';
                }

                if (this.resendingVerificationLinkStatus == 'failed') {
                    return 'Verification link sending failed.';
                }
            },
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            toShow() {
                this.notShowOptions = !this.notShowOptions;
                toastr.clear();
            },

            saveChanges() {
                if (this.syncUrlOfActivePage && !this.saving) {
                    this.saving = true;
                    this.$nextTick(() => {
                        this.$http.put(this.syncUrlOfActivePage, this.valueOfActivePage).then((response) => {
                            this.saving = false;
                            if (response.body.status == 'success' || response.body.status == 'saved-not-verified') {
                                this.showSavedModal();

                                if (this.value.type == 'job_questionnaire_answer'
                                    && _.endsWith(window.location.href, 'from-sign-up')
                                ) {
                                    window.location = url(this.userHome + '?introModal=applying');
                                }
                            }

                            if (response.body.status == 'saved-not-verified') {
                                this.verified = false;
                            }


                        }, (error) => {
                            if (error.status == 422 && error.body.sections) {
                                $.growl.error({
                                    title: 'Error',
                                    message: error.body.sections,
                                });
                            } else {
                                App.error(error);
                            }
                            this.saving = false;
                        });
                    });
                }
            },

            apply() {
                if (!this.applying) {
                    this.applying = true;
                    this.actionPointOptions.text = 'Applying...';
                    this.actionPointOptions.color = 'orange';
                    this.$nextTick(() => {
                        this.$http.post(this.syncUrl, {}).then((response) => {
                            if (response.body.status == 'success') {
                                $.growl.notice({
                                    title: 'Success',
                                    message: 'You have now applied for this job',
                                });

                                if (response.body.hasQuestionnaire) {
                                    window.location = this.syncUrl + '/apply';
                                }
                                this.setButtonToApplied();
                            } else if (response.body.status == 'signup-to-apply') {
                                this.signingUpToApply = true;
                            } else if (response.body.status == 'already-applied') {
                                $.growl.notice({
                                    title: 'Error',
                                    message: 'You have already applied to this job',
                                });

                                this.setButtonToApplied();
                            } else if (response.body.status == 'not-yet-verified') {
                                this.showVerifyModal();
                            }
                            
                            this.applying = false;
                        }, (error) => {
                            App.error(error);
                            this.applying = false;
                        });
                    });
                }
            },

            setButtonToApplied() {
                this.actionPointOptions.text = 'Applied';
                this.actionPointOptions.color = 'red';
                this.applying = false;
            },

            setButtonToApplyNow() {
                this.actionPointOptions.text = 'Apply Now!';
                this.actionPointOptions.color = 'green';
            },

            closeSignupToApplyModal() {
                this.signingUpToApply = false;
                this.setButtonToApplyNow();
            },

            closeLoginToApplyModal() {
                this.loggingInToApply = false;
                this.setButtonToApplyNow();
            },

            closeIntroModal() {
                this.showingIntroModal = false;
            },

            closeSavedModal() {
                this.showingSavedModal = false;
            },

            showVerifyModal() {
                this.showingVerifyModal = true;
            },

            closeVerifyModal() {
                this.showingVerifyModal = false;
            },

            getRandomIntInclusive(min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min + 1)) + min;
            },

            showSavedModal() {
                if (
                    this.activePage == 'main'
                    && this.value.type != 'job_questionnaire_answer'
                    && this.getRandomIntInclusive(1, this.numSaves++) == 1
                ) {
                    this.showingSavedModal = true;
                } else {
                    let message = 'Changes saved successfully';
                    if (this.value.type == 'job_questionnaire_answer') {
                        message = 'Your answers have been saved';
                    }
                    $.growl.notice({
                        title: 'Success',
                        message,
                    });
                }
            },

            showPage(slug) {
                this.activePage = slug.slug;
            },

            switchToLoginToApply(value) {
                this.signingUpToApply = false;
                this.loggingInToApply = true;
            },

            switchToSignupToApply(value) {
                this.loggingInToApply = false;
                this.signingUpToApply = true;
            },

            resendVerificationEmail() {
                if (this.resendingVerificationLinkStatus == 'none') {
                    this.resendingVerificationLinkStatus = 'resending';
                    this.$http.post(url('verify'), {}).then((response) => {
                        if (response.body.status == 'success') {
                            this.resendingVerificationLinkStatus = 'resent';
                        }
                    }, (error) => {
                        this.resendingVerificationLinkStatus = 'failed';
                        App.error(error);
                    });
                }
            },
        },
    };
</script>