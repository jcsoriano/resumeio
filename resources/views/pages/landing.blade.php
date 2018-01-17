@extends('layouts.resume')

@section('content')
	<main-app 
        :value="value"
        :left-navigation-links="leftNavigationLinks"
        :right-navigation-links="rightNavigationLinks"
        :section-types="sectionTypes"
        :action-point-options="actionPointOptions"
        editing
        {!! isset($intent) && $intent ? 'intent="'.$intent.'"' : '' !!}
    ></main-app>
@endsection

@section('main-script')
    <script type="text/javascript" src="{{ asset('js/main-app.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/landing.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/vee-validate/2.0.0-beta.22/vee-validate.min.js"></script>

    <script>
        Vue.use(VeeValidate, {
            errorBagName: 'validationErrors', // change if property conflicts.
            fieldsBagName: 'validationFields', // change if property conflicts.
        });
        
        new Vue({
    	    el: '#app',
    	    data: {
                sectionTypes: [
                    {
                        type: 'bullet-section',
                        name: 'Work Experience',
                        editName: false,
                        image: url('img/work.png'),
                        withDateRange: true,
                        data: [ ],
                        bulletName: 'Work Experience',
                        disableDrag: false,
                     },
                    {
                        type: 'bullet-section',
                        name: 'Education',
                        editName: false,
                        image: url('img/education.png'),
                        withDateRange: true,
                        data: [ ],
                        bulletName: 'Education',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Speaking Engagements',
                        editName: false,
                        image: url('img/speaking.png'),
                        withDateRange: false,
                        data: [ ],
                        bulletName: 'Speaking Engagement',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Awards & Recognitions',
                        editName: false,
                        image: url('img/awards.png'),
                        withDateRange: false,
                        data: [ ],
                        bulletName: 'Recognition',
                        disableDrag: false,
                    },
                    {
                        type: 'rating-section',
                        name: 'Skills',
                        editName: false,
                        image: url('img/skills.png'),
                        data: [ ],
                        bulletName: 'Skill',
                        disableDrag: false,
                    },
                    {
                        type: 'paragraph-section',
                        name: 'About Me',
                        editName: false,
                        image: url('img/about.png'),
                        description: {
                            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse finibus, turpis nec interdum condimentum, arcu enim dignissim augue, a ultricies ligula lacus quis massa. Morbi sit amet imperdiet magna, eu consectetur erat. Vivamus varius, enim eget fringilla tristique, diam erat consequat odio, id iaculis ligula augue sed metus. In et auctor nisl. Fusce cursus, sem ut luctus cursus, elit mi semper risus, nec aliquet neque ligula nec elit.',
                            saved: true,
                            editMode: false,
                        },
                        bulletName: 'About Me',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Bullet with Date Range Section',
                        editName: false,
                        image: '',
                        withDateRange: true,
                        data: [ ],
                        bulletName: 'Bullet Point',
                        disableDrag: false,
                    },
                    {
                        type: 'bullet-section',
                        name: 'Bullet Section',
                        editName: false,
                        image: '',
                        withDateRange: false,
                        data: [ ],
                        bulletName: 'Bullet Point',
                        disableDrag: false,
                    },
                    {
                        type: 'rating-section',
                        name: 'Rating Section',
                        editName: false,
                        image: '',
                        data: [ ],
                        bulletName: 'Rating',
                        disableDrag: false,
                    },
                    {
                        type: 'paragraph-section',
                        name: 'Paragraph Section',
                        editName: false,
                        image: '',
                        description: {
                            text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse finibus, turpis nec interdum condimentum, arcu enim dignissim augue, a ultricies ligula lacus quis massa. Morbi sit amet imperdiet magna, eu consectetur erat. Vivamus varius, enim eget fringilla tristique, diam erat consequat odio, id iaculis ligula augue sed metus. In et auctor nisl. Fusce cursus, sem ut luctus cursus, elit mi semper risus, nec aliquet neque ligula nec elit.',
                            saved: true,
                            editMode: false,
                        },
                        bulletName: 'Paragraph',
                        disableDrag: false,
                    },
                    {
                        type: 'gallery-section',
                        name: 'Gallery',
                        editName: false,
                        image: url('img/camera.png'),
                        data: [ ],
                        bulletName: 'Gallery Item',
                        disableDrag: false,
                    },
                    {
                        type: 'link-section',
                        name: 'Links',
                        editName: false,
                        image: url('img/link.png'),
                        data: [ ],
                        bulletName: 'Link',
                        disableDrag: false,
                    },
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
                            bulletName: 'About Me',
                            description: {
                                text: "My Resumes is the new way of sending and receiving resumes. For individuals, sending emails just got easier - just copy and paste a link, and you're done. No need to attach emails. Construct your own elegant, professional online resume in minutes! For companies, collecting and downloading hundreds of resume attachments are gone. Post your job openings here and enjoy sifting through elegant, professionally-designed resumes without the hassle of organizing and downloading lots of files.",
                            },
                            image: url('img/about.png'),
                            name: 'About My Resumes',
                            type: 'paragraph-section',
                        },
                        {
                            bulletName:"Reason",
                            data: [
                                {
                                    rating:5,
                                    title:"Ease of creating resumes",
                                },
                                {
                                    rating:5,
                                    title:"Ease of sending resumes",
                                },
                                {
                                    rating:5,
                                    title:"Increase in Credibility",
                                },
                            ],
                            image: url('img/skills.png'),
                            name:"Why use My Resumes",
                            type:"rating-section",
                        },
                        {
                            type: 'gallery-section',
                            name: 'For Companies, awesome features',
                            image: url('img/camera.png'),
                            data: [
                                {
                                    // picture: 'https://images.unsplash.com/photo-1440549770084-4b381ce9d988',
                                    picture: url('img/jobpostings.JPG'),
                                    title: 'Post Multiple Job Openings',
                                },
                                {
                                    // picture: 'https://images.unsplash.com/photo-1440549770084-4b381ce9d988',
                                    picture: url('img/questionnaire.JPG'),
                                    title: 'Create Online Interviews',
                                },
                                {
                                    picture: url('img/assess.JPG'),
                                    title: 'Easily Assess Candidates',
                                }
                            ],
                            bulletName: 'Gallery Item',
                        },
                        {
                            bulletName:"bullet point",
                            data: [
                                {
                                    dateFrom:"Start",
                                    dateTo:"Tutorial",
                                    description:"The \"Show Options\" button is in the lower-right of your screen. Clicking it will expose several buttons that will allow you to edit this resume. That's right - this web page was built by our resume builder!",
                                    title:"Click the \"Show Options\" button",
                                },
                                {
                                    dateFrom:"First",
                                    dateTo:"Step",
                                    description:"After clicking the \"Show Options\" button, you should now see different buttons appear on your screen. Let's try adding a new section to this resume - click the dropdown under \"Add New Section\" and choose a section type. Any will do!",
                                    title:"Add a New Section",
                                },
                                {
                                    dateFrom:"Second",
                                    dateTo:"Step",
                                    description:"If you see a button that says \"Add New _____\" that means you chose a section that allows you to add bullet points. Go ahead and click it, enter anything in the text fields, and click \"Save\"! You should now see the bullet point added under  your section.",
                                    title:"Add some bullet points",
                                },
                                {
                                    dateFrom:"Third",
                                    dateTo:"Step",
                                    description: "On this last step we just want you to know that you can re-order anything in your resume. For bullet points, simply drag them to their proper place. For sections, you can drag those black dots at the top, below the \"My Resumes\" name. Finally, everything here is editable when you click the \"Show Options\" button, so try clicking anything and see it turn into a text field. Clicking \"Don't Show Options\" will allow you to preview your resume to see what others will see when you send it.",
                                    title:"Re-order bullet points and sections",
                                },
                            ],
                            image: url('img/work.png'),
                            name:"Experience how easy it is to create your online resume",
                            type:"bullet-section",
                            withDateRange:true,
                        },
                    ],
                },
                actionPointOptions: {
                    text: 'Sign-up For Free!',
                    type: 'component',
                    color: 'purple',
                    component: 'sign-up',
                },
                leftNavigationLinks: [],
                rightNavigationLinks: [{
                    name: 'Login',
                    type: 'component',
                    component: 'log-in',
                }],
    	    },

            mounted() {

            },

            methods: {
                test() {
                    // do nothing
                },
            },
    	});
	</script>
@endsection
