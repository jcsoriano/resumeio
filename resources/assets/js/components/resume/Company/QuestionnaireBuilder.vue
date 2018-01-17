<style scoped>
    .switch-option-label {
        font-size:18px;
    }
    .text-green {
        color: green;
    }
    .text-red {
        color: red;
    }
</style>
<template>
    <div>
        <center>
            <br/>
            <div class="card col-md-4 offset-md-4">
                <h5>
                    Require this questionnaire every time someone applies
                    <br/>
                    <div class="switch">
                        <label>
                            <span class="switch-option-label text-red">No</span>
                            <input type="checkbox" v-model="value.requireOnApply">
                            <span class="lever"></span>
                            <span  class="switch-option-label text-green">Yes</span>
                        </label>
                    </div>
                </h5>
            </div>
        </center>
        <sections :not-show-options="notShowOptions" :value="value.sections" id-modifier="questionnaire-builder" :path="path" questionnaire></sections>
    </div>
</template>
<script>
    export default {
        props: {
            notShowOptions: {
                type: Boolean,
                default: false,
            },
            value: {
                type: Object | Array,
                default() {
                    return {};
                },
            },
            path: {
                type: String,
                required: true,
            },
        },

        mounted() {
            // if sections property doesn't exist, assign it with empty array
            if (!this.value.sections) {
                this.$set(this.value, 'sections', []);
            }

            if (!this.value.requireOnApply) {
                this.$set(this.value, 'requireOnApply', false);
            }

            // if sections doesn't have values yet, assign it with sample sections
            if (this.value.sections.length == 0) {
                this.value.sections = [
                    {
                        name: 'Form Questions',
                        type: 'bullet-section',
                        data: [
                            {
                                answer: '',
                                question: 'Number of Years Experience',
                                type: 'magis-string',
                            },
                            {
                                answer: '',
                                question: 'Available Interview Dates',
                                type: 'magis-string',
                            },
                            {
                                answer: '',
                                question: 'I am available to work starting',
                                type: 'magis-string',
                            },
                        ],
                        image: url('img/work.png'),
                        bulletName: 'Question',
                        question: true,
                    },
                    {
                        name: 'Why do you want to apply for this position?',
                        description: {
                            text: 'I want to apply to this position because...',
                        },
                        image: url('img/about.png'),
                        type: 'paragraph-section',
                        question: true,
                    },
                    {
                        type: 'rating-section',
                        name: 'Please Rate Yourself on the Following Skills',
                        editName: false,
                        image: url('img/skills.png'),
                        data: [
                            {
                                rating: 1,
                                title: 'Multi-tasking',
                            },
                            {
                                rating: 1,
                                title: 'Attention to Detail',
                            },
                            {
                                rating: 1,
                                title: 'Coordination',
                            },
                        ],
                        question: true,
                        bulletName: 'Rating Question',
                        disableDrag: false,
                    },
                    {
                        bulletName: 'Paragraph',
                        description: {
                            editMode: false,
                            editing: false,
                            saved: true,
                            text: 'Just like the resume builder, you can still create normal sections here such as the paragraph section. Here you can put content such as further instructions to your applicant.',
                        },
                        disableDrag: false,
                        editName: false,
                        image: url('img/education.png'),
                        name: 'Further Instructions',
                        question: false,
                        type: 'paragraph-section',
                    },
                ];
            }
        },
    };
</script>