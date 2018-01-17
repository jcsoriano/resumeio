<style>
    .signup.form-control {
        padding:0;
        border: none;
    }
    .signup-url .form-control {
        padding:0;
    }
    .signup-modal-body .error {
        font-size:12px;
    }
</style>
<style scoped>
    .error {
        color: red;
    }
    .signup-url .input-group-addon {
        padding-top: 0;
        padding-bottom: 0;
    }
</style>
<template>
    <modal :id="id" @close="close" :show="show" size-modifier="modal-xs">
        <center slot="modal-title" class="modal-title"><h5>Sign-up for FREE!</h5></center>
        <div slot="modal-body" class="modal-body signup-modal-body">
            <center>
                <p>Create your resume or company profile</p>
                <span v-if="errors.is_company" class="error" v-for="error in errors.is_company" v-text="error">
                </span>
                <div class="col-xs-6">
                    <fieldset class="form-group">
                        <input name="is_company" type="radio" id="is_company-individual" value="0" v-model="isCompany">
                        <label for="is_company-individual">Individual</label>
                    </fieldset>
                </div>
                <div class="col-xs-6">
                    <fieldset class="form-group">
                        <input name="is_company" type="radio" id="is_company-company" value="1" v-model="isCompany">
                        <label for="is_company-company">Company</label>
                    </fieldset>
                </div>
                <magis-string type="email" name="email" label="E-mail" input-class="signup form-control text-xs-center" v-model="email" :error="errors.email" :rules="rules.email" />
                <magis-string type="string" name="name" :label="isCompany == 1 ? 'Company Name' : 'Full Name'" input-class="signup form-control text-xs-center" v-model="name" :error="errors.name" :rules="rules.name" />
                <magis-string type="password" name="password" label="Password" input-class="signup form-control text-xs-center" v-model="password" :error="errors.password" :rules="rules.password" @keyup.enter="signup" />
                <label for="basic-url">Your <span v-text="isCompany == 1 ? 'Company\'s' : 'Resume\'s'"></span> URL</label>
                <div class="md-form input-group signup-url">
                    <span class="input-group-addon" id="url-slug">myresum.es/</span>
                    <magis-string name="slug" type="string" label="URL" input-class="signup form-control text-xs-center" v-model="slug" :error="errors.slug" pattern="[a-zA-Z0-9]+" :rules="rules.slug" />
                </div>
            </center>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-primary btn-sm" @click="signup" v-text="signingUp ? 'Signing-up...' : 'Sign-up'" :disabled="signingUp"></button>
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
            id: {
                type: String,
                default: 'sign-up-modal',
            },
        },

        data() {
            return {
                email: '',
                name: '',
                slug: '',
                password: '',
                isCompany: 0,
                errors: [],
                rules: {
                    email: 'required|email|max:255',
                    name: 'required|max:255',
                    slug: 'required|max:255|alpha_dash',
                    password: 'required|min:6|max:255',
                },
                signingUp: false,
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

            signup() {
                if (!this.signingUp) {
                    this.signingUp = true;
                    this.$http.post(url('register'), {
                        is_company: this.isCompany,
                        email: this.email,
                        name: this.name,
                        slug: this.slug,
                        password: this.password,
                    }).then((response) => {
                        if (response.body.status == 'success') {
                            $.growl.notice({
                                title: 'Success',
                                message: 'You have successfully registered!',
                            });
                            window.location = url('resumes/' + response.body.slug);
                        }
                    }, (error)  => {
                        this.errors = error.body;
                        this.signingUp = false;
                        App.error(error);
                    });
                }
            },
        },
    };
</script>