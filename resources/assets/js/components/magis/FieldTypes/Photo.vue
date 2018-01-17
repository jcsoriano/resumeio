<style>
    .choose .dropzone .dz-message {
        margin: 62px 0;
        cursor:pointer;
    }
    .choose .dropzone .dz-message:hover {
        text-decoration: underline;
    }
</style>

<style scoped>
    .close {
        position:absolute;
        right:25px;
        top:10px;
    }
    .choose {
        height:154px;
        border: 1px solid black;
    }
    .choose .choose-gallery {
        margin-top:62px;
        color: #636b6f;
        cursor: pointer;
    }
    .choose .choose-gallery:hover {
        text-decoration: underline;
    }
    .gallery .col-sm-3 {
        padding:0px;
    }
    .gallery img:hover {
        border: 1px solid blue;
    }
    .dropzone {
        border: none;
        min-height: 148px;
        padding:0;
    }
</style>

<template>
    <div>
        <div class="choose" v-show="currentPage == 'choose'" >
            <form :action="url('upload/photo/public')" :class="'dropzone ' + (files.length ? 'col-xs-6' : 'col-xs-12')" :id="'dropzone-' + this.name">
                <input type="hidden" name="_token" :value="csrfToken" />
                <input v-if="path" type="hidden" name="path" :value="path" />
            </form>
            <div v-show="files.length" class="col-xs-6">
                <center>
                    <div href="#" class="choose-gallery" @click="switchTo('gallery')">Choose from Gallery</div>
                </center>
            </div>
        </div>
        <div class="gallery" v-if="currentPage == 'gallery'">
            <button type="button" class="close" @click="switchTo('choose')" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <a href="#" v-for="f in files" class="col-sm-3" @click="chooseImage(f.photo)">
                <magis-img :src="gallery(f.photo)" aspect-ratio="f.photo_aspect_ratio" class="img-responsive" />
            </a>
        </div>
        <div v-show="currentPage == 'image'">
            <magis-img :src="url(image)" :aspect-ratio="aspectRatio" class="img-responsive" :timeout="timeout" />
            <button type="button" class="close" @click="removeImage" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <input type="hidden" :name="name" :value="image" />
        <div v-show="error" class="error" v-for="e in error" v-text="e"></div>
    </div>
</template>

<script>
    export default {
        props: {
            name: {
                type: String,
                default() {
                    return 'photo-' + Math.floor(Math.random() * 10000);
                },
            },
            path: {
                type: String,
                default: '',
            },
            value: {
                type: String,
                default: '',
            },
            initialError: {
                type: Array,
                default: function () {
                    return [];
                },
            },
            aspectRatio: {
                type: String | Number,
                default: null,
            },
            timeout: {
                type: String | Number,
                default: 200,
            },
            noGallery: {
                type: Boolean,
                default: false,
            },
        },

        computed: {
            title() {
                return _.titleize(this.name);
            },

            csrfToken() {
                return App.csrfToken;
            },
        },

        data() {
            return {
                error: Array.isArray(this.initialError)
                    ? this.initialError
                    : JSON.parse(this.initialError),
                image: this.value,
                oldImage: this.value,
                dropzone: null,
                currentPage: this.value ? 'image' : 'choose',
                files: [],
            };
        },

        watch: {
            value(newVal) {
                this.image = this.value;
                this.dropzone.removeAllFiles(true);
            },

            image(newVal, oldVal) {
                if (!newVal) {
                    this.oldImage = oldVal;
                }
                this.currentPage = newVal ? 'image' : 'choose';
            },

            path(newVal) {
                this.fetchGallery();
            },
        },

        created() {
            this.fetchGallery();
        },

        mounted() {
            this.dropzone = new Dropzone('#dropzone-' + this.name, {
                dictDefaultMessage: 'Upload an Image',
            });

            this.dropzone.on('success', (file, response) => {
                this.image = response.path;
                this.$emit('input', this.image);
            });

            this.dropzone.on('error', (file, errorMessage, xhr) => {
                if (xhr.status != 200) {
                    if (xhr.status == 413) {
                        this.error = ['The file you uploaded is too large, try uploading a smaller file.'];
                    } else {
                        this.error = ['The file you uploaded may be too large.'];
                    }
                }

                this.activePage = 'image';
                this.$nextTick(() => {
                    this.image = this.oldImage;
                    this.$emit('input', this.oldImage);
                });
                this.dropzone.removeAllFiles(true);
            });
        },

        methods: {
            fetchGallery() {
                if (!this.noGallery) {
                    const [resource, id] = this.path.split('/');

                    this.$http.post(url(resource + '/gallery'), { id }).then((response) => {
                        if (response.body.status == 'success') {
                            this.files = response.body.files;
                            this.currentPage = this.image ? 'image' : 'choose';
                        } else {
                            // console.log(response);
                        }
                    }, (error) => {
                        console.log(error);
                    });
                }
            },

            removeImage() {
                this.image = '';
                this.$emit('input', this.image);
            },

            switchTo(page) {
                this.currentPage = page;
            },

            gallery(image) {
                return url(image);
            },

            chooseImage(image) {
                this.image = image;
                this.$emit('input', this.image);
            },

            url(urlString) {
                return url(urlString);
            },
        },
    };
</script>
