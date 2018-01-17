<style>
    .create-job-posting.form-control {
        padding:0;
        /*border: none;*/
    }
    .create-job-posting.form-control.slug {
        border-bottom:1px gray solid;
    }
    .create-job-posting-url .form-control {
        padding:0;
    }
    .create-job-posting-modal-body .error {
        font-size:12px;
    }
</style>
<style scoped>
    .error {
        color: red;
    }
    .create-job-posting-url .input-group-addon {
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
<template>
    <modal :id="id" @close="close" :show="show" size-modifier="modal-xs">
        <center slot="modal-title" class="modal-title"><h5>Create your Job Posting for FREE!</h5></center>
        <div slot="modal-body" class="modal-body create-job-posting-modal-body">
            <div class="row">
                <div class="col-xs-12">
                    <center>
                        <p>Enter the position name that you'll hire for, and your desired share-able URL</p>
                        <div class="col-md-8 offset-md-2">
                            <magis-string type="string" name="name" label="Position Name" input-class="create-job-posting form-control text-xs-center" v-model="name" :error="errors.name" :rules="rules.name" @blur="attemptSlug" />
                        </div>
                        <label for="basic-url">Your Job Posting's URL</label>
                        <div class="md-form input-group create-job-posting-url">
                            <span class="input-group-addon" id="url-slug" v-text="'myresum.es/' + baseSlug + '/'"></span>
                            <magis-string name="slug" type="string" label="URL" input-class="create-job-posting form-control text-xs-center slug" v-model="slug" :error="errors.slug" pattern="[a-zA-Z0-9]+" :rules="rules.slug" @enter="create" />
                        </div>
                    </center>
                </div>
            </div>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" @click="create" v-text="creating ? 'Creating Job Posting...' : 'Create Job Posting'" :disabled="creating"></button>
        </div>
    </modal>
</template>

<script>
    export default {
        props: {
            show: {
                type: Boolean,
                deafault: false,
            },
            value: {
                type: Object,
                default: function () {
                    return {
                        name: '',
                    };
                },
            },
            baseSlug: {
                type: String,
                required: true,
            },
            id: {
                type: String,
                default: 'sign-up-modal',
            },
        },

        data() {
            return {
                name: '',
                slug: '',
                errors: [],
                rules: {
                    name: 'required|max:255',
                    slug: 'required|max:255|alpha_dash',
                },
                creating: false,
            };
        },

        computed: {
            sampleSlug() {
                return _.lowerCase(this.name.replace(/[^A-Z0-9]/ig, ''));
            },
        },

        methods: {
            close() {
                this.$emit('close');
            },

            attemptSlug() {
                if (!this.slug) {
                    this.slug = _.kebabCase(this.name);
                }
            },

            create() {
                if (!this.creating) {
                    this.creating = true;
                    this.$http.post(url('job_postings'), {
                        name: this.name,
                        slug: this.slug,
                    }).then((response) => {
                        if (response.body.status == 'success') {
                            $.growl.notice({
                                title: 'Success',
                                message: 'You have successfully created a job!',
                            });
                            window.location = url(response.body.slug);
                        }

                        if (response.body.status == 'not-yet-verified') {
                            this.creating = false;
                            this.$emit('verificationRequest');
                            this.$emit('close');
                        }
                    }, (error)  => {
                        this.errors = error.body;
                        this.creating = false;
                        App.error(error);
                    });
                }
            },
        },
    };
</script>