<template>
    <figure>
        <div v-if="!value.editing">
            <!--Card-->
            <div class="card">

                <!--Card image-->
                <div class="view overlay hm-white-slight">
                    <img :src="value.picture" class="img-fluid" alt="">
                    <a>
                        <div class="mask waves-effect waves-light"></div>
                    </a>
                </div>
                <!--/.Card image-->

                <!--Social buttons-->
                <div class="card-share">
                    <div class="social-reveal social-reveal-active">
                        <!--Facebook-->
                        <a type="button" class="btn btn-floating blue" @click="editItem()"><i class="fa fa-pencil-square-o"></i></a>
                        <!--Twitter-->
                        <a type="button" class="btn btn-floating red" @click="removeItem()"><i class="fa fa-remove"></i></a>
                    </div>
                </div>
                <!--/Social buttons-->

                <!--Card content-->
                <div class="card-block">
                    <!--Title-->
                    <h6 class="card-title" v-text="value.title"></h6>
                </div>
                <!--/.Card content-->

            </div>
            <!--/.Card-->
        </div>
        <div v-if="value.editing">
            <!-- <input type="text" class="small" v-model="tempValue.picture" :placeholder="'Enter Picture Link'"> -->
            <input type="text" class="small" v-model="tempValue.title" :placeholder="'Enter Title'">
            <magis-photo v-model="tempValue.picture" :path="path" no-gallery ></magis-photo>
            <div class="text-xs-center">
                <button class="btn btn-primary btn-sm" @click="saveItem()">Save</button>
                <button class="btn btn-secondary btn-sm" @click="cancelItem()">Cancel</button>
            </div>
        </div>
    </figure>
</template>


<script>
    export default {
        props: {
            value: {
                required: false,
                type: Object,
            },
            id: {
                type: String | Number,
                default() {
                    return Math.floor(Math.random() * 10000);
                },
            },
            path: {
                type: String,
                required: true,
            },
        },

        beforeMount() {
            this.setValueIfUndefined('editing', false);
            this.setValueIfUndefined('saved', true);
        },

        data() {
            return {
                tempValue: {
                    editing: true,
                    picture: '',
                    title: '',
                    saved: false,
                },
            };
        },

        methods: {
            setValueIfUndefined(property, defaultValue) {
                if (this.value[property] === undefined) {
                    this.$set(this.value, property, defaultValue);
                }
            },

            saveItem: function () {
                if (this.tempValue.pic != '' && this.tempValue.title != '') {
                    this.tempValue.editing = false;
                    this.tempValue.saved = true;
                    this.value.editing = this.tempValue.editing;
                    this.value.picture = this.tempValue.picture;
                    this.value.title = this.tempValue.title;
                    this.value.saved = this.tempValue.saved;
                    this.tempValue = {
                        editing: true,
                        picture: '',
                        title: '',
                        saved: false,
                    };
                } else {
                    toastr.warning('Please Answer all Fields');
                }
            },

            cancelItem() {
                this.tempValue = {
                    editing: true,
                    picture: '',
                    title: '',
                    saved: false,
                };

                if (this.value.saved) {
                    this.value.editing = false;
                } else {
                    this.$emit('removeItem');
                }
            },

            removeItem: function () {
                this.$emit('removeItem');
            },

            editItem: function () {
                this.tempValue = Object.assign({}, this.value);
                this.value.editing = true;
            },
        },
    };
</script>