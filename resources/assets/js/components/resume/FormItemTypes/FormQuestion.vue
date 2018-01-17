<template>
	<div>
        <div class="col-lg-12">
            <div v-if="questionType == 'text'">
                <div v-if="editMode">
                    <input type="text" class="small big" placeholder="Ask Some Questions" v-model="initialContent.question">
                    <div class="text-xs-center">
                        <button class="btn btn-primary btn-sm" @click="saveQuestionText()">Save</button>
                    </div>
                </div>
                <div v-if="!editMode">
                    <h5><span v-text="questionNumber+'.'"></span>&nbsp;<span v-text="content.question"></span></h5>
                    <textarea type="text" id="form76" class="md-textarea small" rows="3" placeholder="Place your answer here..."></textarea>
                    <div class="text-xs-center">
                        <button class="btn btn-primary btn-sm" @click="editQuestion()">Edit</button>
                    </div>
                </div>
            </div>
            <!-- <div v-if="questionType == 'text'">
                <div v-if="initEditMode">
                    <input type="text" class="small big" placeholder="Ask Some Questions" v-model="initialContent.question">
                    <div class="text-xs-center">
                        <button class="btn btn-primary btn-sm" @click="saveQuestionText()">Save</button>
                    </div>
                </div>
            </div> -->
        </div>
	</div>
</template>

<script>
    export default {
        props: {
            questionType: {
                required: true,
                type: String,
            },
            questionNumber: {
                required: true,
                type: Number,
            },
            content: {
                required: true,
                type: Object,
            },
            editMode: {
                required: false,
                default: true,
                type: Boolean,
            },
        },

        data() {
            return {
                initialContent: [],
            };
        },
        
        beforeMount() {
            this.initialContent = Object.assign({}, this.content);
        },

        methods: {
            saveQuestionText() {
                this.content.question = this.initialContent.question;
                this.content.answer = this.initialContent.answer;
                this.$emit('isEditMode');
            },
            editQuestion(){
                this.initialContent = Object.assign({}, this.content);
                this.$emit('isEditMode');
            },

            // onEditMode() {

            // }
        },
    };
</script>