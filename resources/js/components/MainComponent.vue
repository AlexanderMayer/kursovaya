<script setup>
import { ref, onMounted, onBeforeUnmount, computed, provide } from "vue";
import axios from "axios";
import Cookies from "js-cookie";
import { useRouter } from "vue-router";
import { thisUrl } from "../api.js";

const router = useRouter();
let showModal = ref(false);
let intervalId = ref(null);
let user = ref(JSON.parse(localStorage.getItem("user")) || null);
let isNavbarOpen = ref(false);

const data = async () => {
    try {
        const token = Cookies.get('token');
        if (!token) return;
        const response = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        user.value = response.data.data;
        localStorage.setItem("user", JSON.stringify(user.value));
    } catch (error) {
        console.error('Ошибка вывода пользователя', error);
        throw error;
    }
};

const updateUser = (newUser) => {
    user.value = newUser;
    localStorage.setItem("user", JSON.stringify(newUser));
};

async function logout() {
    Cookies.remove('token');
    localStorage.removeItem("user");
    try {
        await axios.get(`${thisUrl()}/auth/logout`).then(res => {
            user.value = null;
            router.push({ name: 'start' });
        });
    } catch (error) {
        console.error('Ошибка при выходе', error);
    }
}

const checkSession = async () => {
    const token = Cookies.get('token');

    if (!token) {
        if (user.value !== null) {
            user.value = null;
            localStorage.removeItem("user");
            openSessionExpiredModal();
        }
        return;
    }

    if (user.value === null) {
        await data();
    }
};

const isLoggedIn = computed(() => user.value !== null);

provide('updateUser', updateUser);
provide('user', user);

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

function toggleNavbar() {
    isNavbarOpen.value = !isNavbarOpen.value;
}

function closeNavbar() {
    isNavbarOpen.value = false;
}

onMounted(() => {
    checkSession();
    intervalId.value = setInterval(checkSession, 7200000);
});

onBeforeUnmount(() => {
    clearInterval(intervalId);
});
</script>

<template>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded shadow-sm">
            <div class="container-fluid">
                <a class="navbar-brand">Аукцион</a>
                <button class="navbar-toggler" type="button" @click="toggleNavbar" :aria-expanded="isNavbarOpen.toString()" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" :class="{ show: isNavbarOpen }" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0" @click="closeNavbar">
                        <li class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'start' }">Главная</router-link>
                        </li>
                        <li v-show="!isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'signUp' }">Регистрация</router-link>
                        </li>
                        <li v-show="!isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'login' }">Войти в личный кабинет</router-link>
                        </li>
                        <li v-show="isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'user' }">Личный кабинет</router-link>
                        </li>
                        <li v-show="isLoggedIn" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'search.lots' }">Поиск по категориям</router-link>
                        </li>
                        <li v-show="user?.role === 2" class="nav-item">
                            <router-link class="nav-link" :to="{ name: 'admin' }">Панель администратора</router-link>
                        </li>
                    </ul>
                    <button v-show="isLoggedIn" class="btn btn-outline-danger" @click.prevent="logout">Выйти</button>
                </div>
            </div>
        </nav>

        <main class="mt-4">
            <router-view></router-view>

            <div v-if="showModal" class="modal show d-block" tabindex="-1">
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

.container {
    max-width: 1820px;
}
</style>
