import { createRouter, createWebHashHistory } from 'vue-router';
import HomeView from '@/views/HomeView.vue';
import JobsView from '@/views/JobsView.vue';
import NotFoundView from '@/views/NotFoundView.vue';
import JobView from '@/views/JobView.vue';
import AddJobView from '@/views/AddJobView.vue';
import EditJobView from '@/views/EditJobView.vue';
import SignUpView from '@/views/SignUpView.vue';
import LogInView from '@/views/LogInView.vue';
import ApplicationView from '@/views/ApplicationView.vue';
import ShowApplicationsView from '@/views/ShowApplicationsView.vue';


const router = createRouter({
    history: createWebHashHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'Home',
            component: HomeView
        },
        {
            path:'/jobs',
            name: 'Jobs',
            component: JobsView
        },
        {
            path:'/jobs/:id',
            name: 'Job',
            component: JobView
        },
        {
            path:'/jobs/add',
            name: 'Add-Job',
            component: AddJobView
        },
        {
            path:'/jobs/:id/edit',
            name: 'Edit-Job',
            component: EditJobView
        },
        {
            path: '/signup',
            name: 'Sign-up',
            component: SignUpView
        },
        {
            path:'/login',
            name: 'Log-in',
            component: LogInView
        },
        {
            path:'/jobs/:id/application',
            name: 'Job-Application',
            component: ApplicationView

        },
        {
            path:'/jobs/:id/applicants',
            name: 'Job-Applicant',
            component: ShowApplicationsView
        },
        {
            path: '/:catchAll(.*)', 
            name: 'Not-Found',
            component: NotFoundView
        },
    ]
});

export default router;