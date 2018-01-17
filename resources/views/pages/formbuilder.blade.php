@extends('layouts.resume')

@section('content')
	<main-app 
        :value="value"
        :left-navigation-links="leftNavigationLinks"
        :right-navigation-links="rightNavigationLinks"
        :section-types="sectionTypes"
        :action-point-text="actionPointText" 
        :action-point-color="actionPointColor" 
        {{-- :action-point-component="actionPointComponent" --}}
        section-type="form-sections"
        init-show-options
        editing
    ></main-app>
@endsection

@section('main-script')
    <script type="text/javascript" src="{{ asset('js/main-app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/form-sections.js') }}"></script>

    <script>
        new Vue({
    	    el: '#app',
    	    data: {
                sectionTypes: [
                    {
                        type: 'paragraph-section',
                        name: 'Essay Question',
                        editName: true,
                        image: url('img/about.png'),
                        description: {
                            text: '',
                            saved: true,
                            editMode: false,
                        },
                        question: true,
                        disableDrag: true,
                    },
                    {
                        type: 'rating-section',
                        name: 'Rating Questions',
                        editName: true,
                        image: '',
                        data: [],
                        bulletName: 'Rating Question',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Form Questions',
                        editName: false,
                        image: url('img/work.png'),
                        withDateRange: true,
                        data: [],
                        question: true,
                        bulletName: 'Question',
                        disableDrag: false,
                    },
                    // {
                    //     type: 'radio-section',
                    //     name: 'Radio Button Question',
                    //     editName: false,
                    //     image: url('img/skills.png'),
                    //     data: [],
                    //     question: true,
                    //     bulletName: 'Radio Button',
                    //     disableDrag: false,
                    // },


                    // {
                    //     type: 'paragraph-section',
                    //     name: 'Additional Instructions',
                    //     editName: false,
                    //     image: url('img/about.png'),
                    //     description: {
                    //         text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse finibus, turpis nec interdum condimentum, arcu enim dignissim augue, a ultricies ligula lacus quis massa. Morbi sit amet imperdiet magna, eu consectetur erat. Vivamus varius, enim eget fringilla tristique, diam erat consequat odio, id iaculis ligula augue sed metus. In et auctor nisl. Fusce cursus, sem ut luctus cursus, elit mi semper risus, nec aliquet neque ligula nec elit.',
                    //         saved: true,
                    //         editMode: false,
                    //     },
                    //     bulletName: 'About Me',
                    //     disableDrag: false,
                    // },
                    // {
                    //     type: 'bullet-section',
                    //     name: 'Bullet with Date Range Section',
                    //     editName: false,
                    //     image: '',
                    //     withDateRange: true,
                    //     data: [ ],
                    //     bulletName: 'Bullet Point',
                    //     disableDrag: false,
                    // },
                    // {
                    //     type: 'bullet-section',
                    //     name: 'Bullet Section',
                    //     editName: false,
                    //     image: '',
                    //     withDateRange: false,
                    //     data: [ ],
                    //     bulletName: 'Bullet Point',
                    //     disableDrag: false,
                    // },
                    // {
                    //     type: 'rating-section',
                    //     name: 'Rating Section',
                    //     editName: false,
                    //     image: '',
                    //     data: [ ],
                    //     bulletName: 'Rating',
                    //     disableDrag: false,
                    // },
                    // {
                    //     type: 'paragraph-section',
                    //     name: 'Paragraph Section',
                    //     editName: false,
                    //     image: '',
                    //     description: {
                    //         text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse finibus, turpis nec interdum condimentum, arcu enim dignissim augue, a ultricies ligula lacus quis massa. Morbi sit amet imperdiet magna, eu consectetur erat. Vivamus varius, enim eget fringilla tristique, diam erat consequat odio, id iaculis ligula augue sed metus. In et auctor nisl. Fusce cursus, sem ut luctus cursus, elit mi semper risus, nec aliquet neque ligula nec elit.',
                    //         saved: true,
                    //         editMode: false,
                    //     },
                    //     bulletName: 'Paragraph',
                    //     disableDrag: false,
                    // },
                    // {
                    //     type: 'gallery-section',
                    //     name: 'Gallery',
                    //     editName: false,
                    //     image: url('img/camera.png'),
                    //     data: [ ],
                    //     bulletName: 'Gallery Item',
                    //     disableDrag: false,
                    // },
                    // {
                    //     type: 'link-section',
                    //     name: 'Links',
                    //     editName: false,
                    //     image: url('img/link.png'),
                    //     data: [ ],
                    //     bulletName: 'Link',
                    //     disableDrag: false,
                    // },
                ],
                value: {
                    profile: {
                        name: 'My Resumes',
                        position: 'The new way of sending resumes',
                    },
                    bannerPicture: url('img/company.jpeg'),
                    profilePicture: url('img/companyprof.jpg'),
                    leftSnapshot: {
                        title: 'Why create an Online Resume',
                        items: [
                            {
                                title: '1',
                                description: 'It\'s FREE',
                            },
                            {
                                title: '2',
                                description: 'It\'s CONVENIENT',
                            },
                            {
                                title: '3',
                                description: 'It\'s how EVERYONE will do it',
                            },
                        ]
                    },
                    rightSnapshot: {
                        title: 'Why Post Job Openings Here',
                        items: [
                            {
                                title: '1',
                                description: 'It\'s FREE',
                            },
                            {
                                title: '2',
                                description: 'It\'s CONVENIENT',
                            },
                            {
                                title: '3',
                                description: 'It\'s how EVERYONE will do it',
                            },
                        ]
                    },
                    sections: [
                        {
                            name: "Form Questions",
                            type: "bullet-section",
                            data: [
                                {
                                    answer: "",
                                    question: "Number of Years Experience",
                                    type: "magis-string",
                                },
                                {
                                    answer: "",
                                    question: "Available Interview Dates",
                                    type: "magis-string",
                                },
                                {
                                    answer: "",
                                    question: "I am available to work starting",
                                    type: "magis-string",
                                },
                            ],
                            image: url('img/work.png'),
                            bulletName: "Question",
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
                        // {
                        //     type: 'radio-section',
                        //     name: 'What type of person are you?',
                        //     editName: false,
                        //     image: url('img/skills.png'),
                        //     data: [
                        //         {
                        //             selected: false,
                        //             title: 'Introvert',
                        //             saved: true,
                        //         },
                        //         {
                        //             selected: false,
                        //             title: 'Extrovert',
                        //             saved: true,
                        //         },
                        //         {
                        //             selected: false,
                        //             title: 'Ambivert',
                        //             saved: true,
                        //         },
                        //     ],
                        //     question: true,
                        //     bulletName: 'Radio Button',
                        //     disableDrag: false,
                        // },
                    ],
                },
                actionPointText: 'Sign-up For Free!',
                actionPointColor: 'purple',
                // actionPointComponent: 'sign-up',
                leftNavigationLinks: [],
                rightNavigationLinks: [],
    	    },

            methods: {
                test() {
                    // do nothing
                },
            },
    	});
	</script>
@endsection
