<template>
    <div>
        <textarea 
            :class="'form-control summernote-' + name" 
            :id="name" 
            :name="name"
            :placeholder="title" 
            :value="value"
            @input="input"
        ></textarea>
        <font v-if="error" color="red" v-for="e in parsedError" v-text="e"></font>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                required: true
            },
            value: {
                type: String,
                default: ''
            },
            path: {
                type: String,
                default: ''
            },
            error: {
                type: Array | String,
                default: function () {
                    return [];
                }
            }
        },
        computed: {
            title() {
                return _.titleize(this.name);
            }
        },
        data() {
            return {
                parsedError: Array.isArray(this.error) ? this.error : JSON.parse(this.error)
            }
        },
        mounted() {
            //put in document.ready to make sure summernote has been loaded first
            $(document).ready(function () {
                var jSummernote = $('.summernote-' + this.name);
                jSummernote.summernote({
                    dialogsInBody: true,
                    height:200,
                    callbacks: {
                        onImageUpload: function(files) {
                            var file = files[0];
                            var data = new FormData();
                            data.append('file', file);
                            data.append('path', this.path);
                            this.$http.post(this.url('upload/photo/public'), data).then(function(response) {
                                jSummernote.summernote('insertImage', this.url(response.body.path), function($image) {
                                    $image.attr('src', this.url(response.body.path));
                                }.bind(this));
                            }.bind(this), function(error) {
                                console.log(error);
                            });
                        }.bind(this),
                        onChange: function(contents, $editable) {
                            this.$emit('input', contents);
                        }.bind(this)
                    }
                });
            }.bind(this));
        },
        methods: {
            input(event) {
                this.$emit('input', event.target.value);
            },
            url(urlString) {
                return url(urlString);
            }
        }
    }
</script>
