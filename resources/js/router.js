import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    // {   path: '/kurs2.2/public/',
    //     component: () => import('./components/MainComponent.vue'), name:'main'
    // },
    {   path: '/kurs2.2/public/start',
        component: () => import('./components/StartPageComponent.vue'), name:'start'
    },
    {   path: '/kurs2.2/public/test2',
        component: () => import('./components/Test2Component.vue'), name:'test2'
    },
    {   path: '/kurs2.2/public/signup',
        component: () => import('./components/SignUpComponent.vue'), name:'signUp'
    },
    {   path: '/kurs2.2/public/login',
        component: () => import('./components/LoginComponent.vue'), name:'login'
    },
    {   path: '/kurs2.2/public/show/:id',
        component: () => import('./components/LotComponent.vue'), name:'show'
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
