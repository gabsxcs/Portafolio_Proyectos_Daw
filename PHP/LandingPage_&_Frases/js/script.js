// Función para seguir al cuadro paso
function nextStep(step) {
	const currentStep = document.getElementById(`paso${step}`);
	const nextStep = document.getElementById(`paso${step + 1}`);

	currentStep.classList.remove('activo');
	nextStep.classList.add('activo');
}

// Función para regresar al cuadro anterior
function prevStep(step) {
	const currentStep = document.getElementById(`paso${step}`);
	const prevStep = document.getElementById(`paso${step - 1}`);

	currentStep.classList.remove('activo');
	prevStep.classList.add('activo');
}

// Esto es para cuando el usuario presione "No soy un robot" que se cambie la imagen 
const checkbox = document.getElementById('robotCheckbox');
const robotImage = document.querySelector('.robotImg');

if (checkbox) {
	checkbox.addEventListener('change', () => {
		if (checkbox.checked) {
			robotImage.src = '../images/robot2.png';
			robotImage.style.transform = 'scale(1.2)';
		} else {
			robotImage.src = '../images/robot.png';
			robotImage.style.transform = 'scale(1)';
		}
	});
}

