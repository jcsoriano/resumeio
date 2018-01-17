<style scoped>
    .flex-row {
        margin-top:25px;
    }
</style>
<template>
    <div>
        <div v-if="value" class="flex-row">
            <div v-for="item in value" class="col-lg-4 col-md-6 col-sm-12 col-xs-12" >
                <a :href="jobUrl(item.slug)" class="color-initial">
                    <div class="card testimonial-card hoverable">
                        <div class="card-up default-color-dark" :style="'background: url('+ (item.bannerPicture ? item.bannerPicture : defaultBanner ) +') no-repeat center center;'">
                        </div>
                        <div class="avatar">
                            <img :src="item.profilePicture ? item.profilePicture : defaultProfile" class="img-responsive">
                        </div>
                        <div class="card-block">
                            <h4 class="card-title" v-text="item.profile.name"></h4>
                            <!-- <p class="text-xs-right no-marg-btm">
                                <b>
                                    <span>0</span>
                                    Applicants
                                </b>
                            </p> -->
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div v-else>
            <center><p>There are no job postings to show</p></center>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Array,
                default() {
                    return [];
                },
            },
            companySlug: {
                type: String,
                required: true,
            },
        },

        data() {
            return {
                defaultProfile: url('img/user.png'),
                defaultBanner: url('img/default.jpg'),
            };
        },

        methods: {
            jobUrl(slug) {
                return url(this.companySlug + '/' + slug);
            },
        },
    };
</script>