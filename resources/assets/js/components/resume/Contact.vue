<style scoped>
    .error {
        color: red;
    }
</style>
<template>
    <modal id="contactMe" @close="close" :show="show">
        <h5 slot="modal-title" class="modal-title">Contact <span v-text="value.name"></span></h5>
        <div slot="modal-body" class="modal-body">
            <div v-if="!loggedIn" class="md-form">
                <input type="email" id="yourcontact" class="form-control" v-model="email">
                <label for="yourcontact" class="">Enter Your Email</label>
                <small><span class="error" v-for="error in errors.email" v-text="error"></span></small>
            </div>
            <div v-if="!loggedIn" class="md-form">
                <input type="email" id="yourcontact" class="form-control" v-model="name">
                <label for="yourcontact" class="">Enter Your Name</label>
                <small><span class="error" v-for="error in errors.name" v-text="error"></span></small>
            </div>
            <div class="md-form">
                <textarea id="myMessage" class="md-textarea" v-model="message"></textarea>
                <label for="myMessage">Message</label>
                <small><span class="error" v-for="error in errors.message" v-text="error"></span></small>
            </div>
        </div>
        <div slot="modal-footer" class="modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" @click="close">Close</button>
            <button type="button" :class="'btn btn-sm ' + (sent ? 'btn-success' : 'btn-primary')" @click="sendMessage" :disabled="sending || sent" v-text="buttonText"></button>
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
                default() {
                    return {};
                },
            },
            slug: {
                type: String,
                default: '',
            },
            loggedIn: {
                type: Boolean,
                default: false,
            },
            resetOnReshow: {
                type: Boolean,
                default: false,
            },
        },

        data() {
            return {
                message: '',
                email: '',
                name: '',
                sending: false,
                sent: false,
                errors: {},
            };
        },

        watch: {
            show() {
                if (this.resetOnReshow) {
                    this.message = '';
                    this.sending = false;
                    this.sent = false;
                    this.errors = {};
                }
            },
        },

        computed: {
            buttonText() {
                if (this.sent) {
                    return 'Message sent!';
                }

                if (this.sending) {
                    return 'Sending...';
                }

                return 'Send Message';
            },
        },

        methods: {
            close() {
                this.$emit('close');
            },

            sendMessage() {
                if (!this.sending && !this.sent) {
                    this.sending = true;
                    this.$nextTick(() => {
                        this.$http.post(url(this.slug), { 
                            message: this.message,
                            name: this.name,
                            email: this.email,
                        }).then((response) => {
                            $.growl.notice({
                                title: 'Success',
                                message: 'Your message has been sent',
                            });
                            this.sending = false;
                            this.sent = true;
                        }, (error) => {
                            App.error(error);
                            this.errors = error.body;
                            this.sending = false;
                        });
                    });
                }
            },
        },
    };
</script>