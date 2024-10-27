import {createRouter, createWebHistory} from 'vue-router';


const routes = [
    {   path: '/kurs2.2/public/start',
        component: () => import('./components/StartPageComponent.vue'), name:'start'
    },
    {   path: '/kurs2.2/public/signup',
        component: () => import('./components/Auth/SignUpComponent.vue'), name:'signUp'
    },
    {   path: '/kurs2.2/public/login',
        component: () => import('./components/Auth/LoginComponent.vue'), name:'login'
    },
    {   path: '/kurs2.2/public/show/:id',
        component: () => import('./components/Lot/LotComponent.vue'), name:'show'
    },
    {   path: '/kurs2.2/public/user',
        component: () => import('./components/User/UserComponent.vue'), name:'user'
    },
    {   path: '/kurs2.2/public/lot/create',
        component: () => import('./components/User/UserLotCreateComponent.vue'), name:'create'
    },
    {   path: '/kurs2.2/public/user/edit',
        component: () => import('./components/User/UserEditComponent.vue'), name:'user.edit'
    },
    {   path: '/kurs2.2/public/admin/panel',
        component: () => import('./components/Admin/AdminComponent.vue'), name:'admin'
    },
    {   path: '/kurs2.2/public/admin/lots',
        component: () => import('./components/Admin/AdminLotsShowComponent.vue'), name:'admin.lots'
    },
    {   path: '/kurs2.2/public/admin/users',
        component: () => import('./components/Admin/AdminUsersShowComponent.vue'), name:'admin.users'
    },
    {   path: '/kurs2.2/public/user/restore',
        component: () => import('./components/User/UserRestoreComponent.vue'), name:'user.restore'
    },
    {   path: '/kurs2.2/public/user/lots/own',
        component: () => import('./components/User/UserLotsComponent.vue'), name:'user.lots'
    },
    {   path: '/kurs2.2/public/search/lots',
        component: () => import('./components/User/UserSearchLotsComponent.vue'), name:'search.lots'
    },
];
const router = createRouter({
    history: createWebHistory(),
    routes: routes,
});

export default router;
