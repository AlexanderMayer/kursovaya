<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import Cookies from "js-cookie";
import {thisUrl} from "../../api.js";

const router = useRouter();
let users = ref([]);
const searchQuery = ref('');

const data = async () => {
    try {
        const token = Cookies.get('token');
        const response = await axios.post(`${thisUrl()}/admin/users`, {
            headers: {
                Authorization: `Bearer ${token}`
            }
        });
        users.value = response.data;
    } catch (error) {
        console.error('Ошибка вывода пользователей', error);
        throw error;
    }
};

const filteredUsers = computed(() => {
    if (!searchQuery.value) return users.value;
    return users.value.filter(user =>
        user.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.surname.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.login.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
        user.email.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

function showUser(id) {
    const token = Cookies.get('token');
    if (!token) {
        router.push({ name: 'login' });
    } else {
        router.push({
            name: 'admin.user',
            params: { id: id }
        });
    }
}

onMounted(() => {
    data();
});
</script>

<template>
    <div class="container">
        <h2 class="my-4">Список пользователей</h2>
        <input type="text" v-model="searchQuery" placeholder="Для поиска пользователя введите имя или фамилию или логин или почту..." class="form-control mb-3 w-100"/>
        <div class="row">
            <div class="user-item" v-for="user in filteredUsers" :key="user.id">
                <div @click.prevent="showUser(user.id)">
                    <span>Имя: {{ user.name }}</span>
                    <span> Фамилия: {{ user.surname }}</span>
                    <span> Логин: {{ user.login }}</span>
                    <span> Почта: {{ user.email }}</span>
                    <span v-show="user.role_id === 1 "> Роль: Пользователь</span>
                    <span v-show="user.role_id === 2 "> Роль: Администратор</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.user-item {
    display: inline-block;
    margin: 10px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.container {
    max-width: 1820px;
}
</style>
