<style scoped>
.magis-img {
    width: 100% !important;
}
</style>

<template>
    <img class="img-responsive magis-img" :src="computedSrc" />
</template>

<script>
    export default {
        props: {
            src: {
                required: false,
            },
            aspectRatio: {
                required: false,
            },
            timeout: {
                required: false,
                type: String | Number,
                default: 50,
            },
            width: {
                required: false,
            },
        },

        data() {
            return {
                computedSrc: '',
            };
        },

        watch: {
            src() {
                this.computeSrc();
            },

            aspectRatio() {
                this.computeSrc();
            },

            width() {
                this.computeSrc();
            },
        },
        mounted() {
            this.computeSrc();

            const throttledComputeSrc = _.throttle(() => {
                this.computeSrc();
            }, 3000, { leading: false });

            // just use jquery to avoid all the cross-browser work
            $(window).resize(throttledComputeSrc);
        },

        methods: {
            computeSrc() {
                this.$nextTick(() => {
                    // setTimeout to handle any animations
                    setTimeout(() => {
                        // use jQuery to avoid cross-browser work
                        let size = this.width ? this.width : $(this.$el).width();

                        if (size
                            && this.src
                            && this.src.includes('googleusercontent')
                        ) {
                            // if height is greater than width, compute the "length"
                            // for your desired width
                            if (this.aspectRatio && this.aspectRatio > 1) {
                                size = size * this.aspectRatio;
                            }

                            this.computedSrc = this.src + '=s' + parseInt(size);
                        } else {
                            this.computedSrc = this.src;
                        }
                    }, this.timeout);
                });
            },
        },
    };
</script>

