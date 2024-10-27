<script setup>
import {ref, onMounted, onBeforeUnmount, watchEffect, onUpdated, onBeforeUpdate} from "vue";
import axios from "axios";
import Cookies from "js-cookie";
import { useRouter } from "vue-router";

const router = useRouter();
const showModal = ref(false);
let intervalId = ref(null);
let user = ref([]);
const isLoggedIn = ref(!!Cookies.get('token'))

const data = async () => {
    try {
        const token = Cookies.get('token');
        if (!token) return;
        const response = await axios.post('http://localhost/kurs2.2/public/api/user/edit', {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data.data;
    } catch (error) {
        console.error('Ошибка вывода пользователя', error);
        throw error;
    }
}

const getToken = () => {
    isLoggedIn.value = !!Cookies.get('token');
};

async function logout() {
    Cookies.remove('token');
    getToken();
    try {
        await axios.get('api/auth/logout')
            .then( res => {
                showModal.value = false;
                router.push({ name: 'start' });
    });
    } catch (error) {
        console.error('Ошибка при выходе', error);
    }
}

const checkSession = async () => {
    const token = Cookies.get('token');
    if (!token && isLoggedIn.value) {
        Cookies.remove('token');
        openSessionExpiredModal();
    }
}

function openSessionExpiredModal() {
    showModal.value = true;
}

function closeModal() {
    showModal.value = false;
}

function goToLogin() {
    closeModal();
    router.push({ name: 'login' });
}

function goToHome() {
    closeModal();
    router.push({ name: 'start' });
}

watchEffect(() => {
    if (isLoggedIn.value) {
        data();
    }
});

onMounted(() => {
    data();
    checkSession();
    intervalId = setInterval(checkSession, 14400000);
});

onBeforeUnmount(() => {
    clearInterval(intervalId);
});

</script>

<template>
    <div class="container my-4">
        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand">Аукцион</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'start' }">Главная</router-link>
                        </li>
                        <li v-show="!isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'signUp' }">Регистрация</router-link>
                        </li>
                        <li v-show="!isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'login' }">Войти в IT</router-link>
                        </li>
                        <li v-show="isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'user' }">Личный кабинет</router-link>
                        </li>
<!--                        <li v-show="isLoggedIn" class="nav-item">-->
<!--                            <router-link class="nav-link" :to="{ name: 'user' }">Поиск по категориям</router-link>-->
<!--                        </li>-->
                        <li v-show="user.role === 2" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'admin' }">Панель администратора</router-link>
                        </li>
                    </ul>
                    <button v-show="isLoggedIn" class="btn btn-outline-danger" @click.prevent="logout">Выйти</button>
                </div>
            </div>
        </nav>

        <main class="mt-4">
            <router-view></router-view>

            <div v-if="showModal && isLoggedIn" class="modal show d-block" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content bg-dark-subtle">
                        <div class="modal-header">
                            <h5 class="modal-title">Сессия завершена</h5>
                            <button type="button" class="btn-close" @click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Ваша сессия истекла. Пожалуйста, войдите снова.</p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" @click="goToLogin">Авторизация</button>
                            <button class="btn btn-secondary" @click="goToHome">Главная страница</button>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</template>

<style scoped>
.navbar-brand {
    font-weight: bold;
    font-size: 1.25rem;
}
.modal {
    display: block;
}
</style>
