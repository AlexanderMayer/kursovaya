<script setup>
import { ref, inject } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Cookies from 'js-cookie';
import { thisUrl } from "../../api.js";

const router = useRouter();
let updateUser = inject('updateUser');
let email = ref('');
let password = ref('');
let emailError = ref('');
let passwordError = ref('');
let loginError = ref('');

const validateForm = () => {
    emailError.value = '';
    passwordError.value = '';
    let isValid = true;

    if (!email.value) {
        emailError.value = 'Пожалуйста, введите почту.';
        isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        emailError.value = 'Пожалуйста, введите корректный адрес электронной почты.';
        isValid = false;
    }

    if (!password.value) {
        passwordError.value = 'Пожалуйста, введите пароль.';
        isValid = false;
    }

    return isValid;
};

const login = async () => {
    loginError.value = '';

    if (!validateForm()) return;

    try {
        const response = await axios.post(`${thisUrl()}/auth/login`, {
            email: email.value,
            password: password.value,
        });

        const token = response.data.access_token;
        Cookies.set('token', token, {expires: 240 / 1440});

        const responseUser = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });

        updateUser(responseUser.data.data);

        await router.push({name: 'start'});
    } catch (error) {
        console.error('Ошибка при входе', error);
        loginError.value = 'Неверный логин или пароль.';
    }
};

function userRestore() {
    const token = Cookies.get('token');
    if (!token) {
        router.push({name: 'user.restore'});
    } else {
        router.push({name: 'start'});
    }
}
</script>

<template>
    <div class="container mt-4">
        <div class="w-50 mx-auto">
            <h3>Авторизация</h3>
            <div v-if="loginError" class="alert alert-danger">{{ loginError }}</div>
            <input type="email" v-model="email" class="form-control my-3" placeholder="Почта">
            <span v-if="emailError" class="text-danger">{{ emailError }}</span>
            <input type="password" v-model="password" class="form-control my-3" placeholder="Пароль">
            <span v-if="passwordError" class="text-danger">{{ passwordError }}</span>
            <input type="submit" @click.prevent="login" class="btn btn-success w-100 mb-2" value="Войти">
            <input type="submit" @click.prevent="userRestore" class="btn btn-secondary w-100" value="Забыли пароль?">
        </div>
    </div>
</template>

<style scoped>

</style>
