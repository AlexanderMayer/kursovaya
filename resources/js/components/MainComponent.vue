<script setup>
import axios from "axios";
import Cookies from "js-cookie";
import {useRouter} from "vue-router";

let router = useRouter();

function logout() {
    Cookies.remove('token');
    const response = axios.get('api/auth/logout');
    router.push({
        name: 'start',
    })
}
</script>

<template>
    <div class="container border border-1 rounded">
        <nav>
            <router-link class="m-2" :to="{name: 'start'}">Главная</router-link>
            <router-link class="m-2" :to="{name: 'signUp'}">Регистрация</router-link>
            <router-link class="m-2" :to="{name: 'login'}">Войти в IT</router-link>
            <router-link class="m-2" :to="{name: 'user'}">Личный кабинет</router-link>
            <router-link class="m-2" :to="{name: 'admin'}">Панель администратора</router-link>
            <a class="m-2" @click.prevent="logout">Выйти</a>
        </nav>
        <main>
            <router-view></router-view>
        </main>
    </div>
</template>

<style scoped>

</style>
