<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const email = ref('');
const emailError = ref('');
const formError = ref('');
const showSuccessModal = ref(false);

const validateForm = () => {
    emailError.value = '';
    formError.value = '';

    let isValid = true;

    if (!email.value) {
        emailError.value = 'Пожалуйста, введите почту.';
        isValid = false;
    } else if (!/\S+@\S+\.\S+/.test(email.value)) {
        emailError.value = 'Пожалуйста, введите корректный адрес электронной почты.';
        isValid = false;
    }

    return isValid;
};

const data = async () => {
    formError.value = '';
    if (!validateForm()) return;

    try {
        await axios.post('http://localhost/kurs2.2/public/api/user/restore', {
            email: email.value
        });
        showSuccessModal.value = true;

        setTimeout(() => {
            showSuccessModal.value = false;
            router.push({name: 'login'});
        }, 5000);
    } catch (error) {
        console.error('Ошибка при вводе данных', error);
        formError.value = 'Произошла ошибка при восстановлении пароля. Попробуйте снова.';
    }
};

</script>

<template>
    <div class="container mt-4">
        <div class="w-50 mx-auto">
            <h3>Восстановление пароля</h3>
            <input type="email" v-model="email" class="form-control my-3" placeholder="Почта">
            <span v-if="emailError" class="text-danger">{{ emailError }}</span>

            <input type="submit" @click.prevent="data" class="btn btn-success w-100" value="Восстановить пароль">
            <span v-if="formError" class="text-danger mt-3">{{ formError }}</span>
        </div>

        <div v-if="showSuccessModal" class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Успех</h5>
                    </div>
                    <div class="modal-body">
                        <p>Письмо с инструкциями по восстановлению пароля отправлено на указанную почту!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.modal.fade.show.d-block {
    opacity: 1;
    transition: opacity 1s ease-in-out;
}
</style>
