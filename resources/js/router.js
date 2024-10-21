import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    {   path: '/kurs2.2/public/',
        component: () => import('./components/MainComponent.vue'), name:'main'
    },
    {   path: '/kurs2.2/public/test',
        component: () => import('./components/TestComponent.vue'), name:'test'
    },
    {   path: '/kurs2.2/public/test2',
        component: () => import('./components/Test2Component.vue'), name:'test2'
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
