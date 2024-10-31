<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRoute } from 'vue-router';
import { useRouter } from 'vue-router';
import Cookies from "js-cookie";
import {thisUrl} from "../../api.js";

const router = useRouter();
const route = useRoute();
let role = ref('');

const data = async () => {
    try {
        const token = Cookies.get('token');
        const response = await axios.get(`${thisUrl()}/admin`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
    } catch (error) {
        console.error('Ошибка данных', error);
        throw error;
    }
}

async function allLots() {
    try {
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        role.value = response.data.data.role;
        if (role.value === '1') {
            router.push({ name: 'login' });
        } else {
            router.push({ name: 'admin.lots' });
        }
    } catch (error) {
        console.error('Ошибка просмотра лотов', error);
        throw error;
    }
}

async function allUsers() {
    try {
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/user/edit`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        role.value = response.data.data.role;
        if (role.value === '1') {
            router.push({ name: 'login' });
        } else {
            router.push({ name: 'admin.users' });
        }
    } catch (error) {
        console.error('Ошибка просмотра пользователей', error);
        throw error;
    }
}

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container text-center mt-5">
        <h3>Панель администратора</h3>
        <div class="d-flex flex-column align-items-center">
            <input type="submit" @click.prevent="allLots" class="btn btn-success m-3 w-50" value="Посмотреть все лоты">
            <input type="submit" @click.prevent="allUsers" class="btn btn-success m-3 w-50" value="Посмотреть всех пользователей">
        </div>
    </div>
</template>

<style scoped>

</style>
