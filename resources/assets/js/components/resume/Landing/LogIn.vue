<style>
    .login.form-control {
        padding:0;
        border: none;
    }
</style>
<style scoped>
    .error {
        color: red;
    }
</style>
<template>
    <modal :id="id" @close="close" :show="show" size-modifier="modal-xs">
        <center slot="modal-title" class="modal-title"><h5>Log-in</h5></center>
        <div slot="modal-body" class="modal-body">
            <center>
                <p>Welcome Back! <span v-if="applyTo">Log-in to apply to this Job Posting</span></p>
                <magis-string type="email" label="E-mail" input-class="login form-control text-xs-center" v-model="email" :error="errors.email" />
                <magis-string type="password" label="Password" input-class="login form-control text-xs-center" v-model="password" :error="errors.password" @enter="login" />
                <fieldset class="form-group">
                    <input name="remember" type="checkbox" id="remember" value="1" v-model="remember">
                    <label for="remember">Remember me</label>
                </fieldset>
            </center>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-primary btn-block btn-sm" @click="login" v-text="loggingIn ? 'Logging-in...' : 'Log-in'" :disabled="loggingIn"></button>
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
                default: 'log-in-modal',
            },
            redirectToHome: {
                type: Boolean,
                default: true,
            },
            applyTo: {
                type: String,
                default: '',
            },
        },

        data() {
            return {
                email: '',
                password: '',
                remember: 0,
                errors: [],
                loggingIn: false,
            };
        },

        methods: {
            close() {
                this.$emit('close');
            },

            login() {
                if (!this.loggingIn) {
                    this.loggingIn = true;
                    this.$nextTick(() => {
                        this.$http.post(url('login'), {
                            email: this.email,
                            password: this.password,
                            remember: this.remember,
                            apply_to: this.applyTo,
                        }).then((response) => {
                            if (response.body.status == 'success') {
                                if (this.applyTo) {
                                    $.growl.notice({
                                        title: 'Success',
                                        message: 'You have now applied!',
                                    });
                                    
                                    if (response.body.hasQuestionnaire) {
                                        window.location = url(this.applyTo + '/apply');
                                    } else {
                                        window.location.reload();
                                    }
                                } else {
                                    this.notifySuccessfulLogin();
                                    window.location = url('home');
                                }
                            } else if (response.body.status == 'already-applied') {
                                this.notifySuccessfulLogin();
                                $.growl.warning({
                                    title: 'Notice',
                                    message: 'You have already applied to this Job Posting!',
                                });
                                window.location.reload();
                            } else if (response.body.status == 'not-yet-verified') {
                                this.notifySuccessfulLogin();
                                window.location = response.body.slug;
                            }
                        }, (error)  => {
                            this.errors = error.body;
                            App.error(error);
                            this.loggingIn = false;
                        });
                    });
                }
            },

            notifySuccessfulLogin() {
                $.growl.notice({
                    title: 'Success',
                    message: 'You are now logged-in!',
                });
            },
        },
    };
</script>