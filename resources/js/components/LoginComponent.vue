<script setup>
import {ref} from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');
const password = ref('');

const login = async () => {
    try {
        const response = await axios.post(
            'api/auth/login', {
                email : email.value,
                password : password.value,
            }
        ).then( res => {
            const token = res.data.access_token;
            localStorage.setItem('token', token);
            router.push({name: 'start'});
        })
    } catch (error) {
        console.error('Ошибка при входе',error);
        throw error;
    }
}
</script>

<template>
    <div class="w-25">
        <input type="email" v-model="email" class="form-control m-3" placeholder="Почта">
        <input type="password" v-model="password" class="form-control m-3" placeholder="Пароль">
        <input type="submit" @click.prevent="login" class="btn btn-success m-3" value="Войти">
    </div>
</template>

<style scoped>

</style>
