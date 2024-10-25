import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    {   path: '/kurs2.2/public/start',
        component: () => import('./components/StartPageComponent.vue'), name:'start'
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
    {   path: '/kurs2.2/public/user/edit',
        component: () => import('./components/UserComponent.vue'), name:'user'
    },
    {   path: '/kurs2.2/public/lot_create',
        component: () => import('./components/LotCreateComponent.vue'), name:'create'
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
