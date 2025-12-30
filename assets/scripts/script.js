//=== FORMULAIRE DE CONTACT (Backend PHP) ===
const formulaire = document.querySelector('#contactform');

if (formulaire) {
    formulaire.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const submitBtn = formulaire.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Désactiver le bouton pendant l'envoi
        submitBtn.disabled = true;
        submitBtn.textContent = 'Envoi en cours...';
        submitBtn.style.cursor = 'not-allowed';
        
        // Récupérer les données du formulaire
        const formData = new FormData(formulaire);
        
        try {
            const response = await fetch('send-email.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            // Créer et afficher le message de retour
            const messageDiv = document.createElement('div');
            messageDiv.style.cssText = `
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 8px;
                text-align: center;
                font-weight: 500;
                animation: slideIn 0.3s ease;
            `;
            
            if (result.success) {
                messageDiv.style.background = 'linear-gradient(135deg, rgba(127, 90, 131, 0.2), rgba(13, 50, 77, 0.2))';
                messageDiv.style.color = '#7F5A83';
                messageDiv.style.border = '2px solid #7F5A83';
                messageDiv.innerHTML = `
                    <strong>✓ Message envoyé avec succès !</strong><br>
                    <small>Vous recevrez une confirmation par email.</small>
                `;
                formulaire.reset();
            } else {
                messageDiv.style.background = 'rgba(255, 0, 0, 0.1)';
                messageDiv.style.color = '#dc3545';
                messageDiv.style.border = '2px solid #dc3545';
                messageDiv.innerHTML = `
                    <strong>✗ Erreur lors de l'envoi</strong><br>
                    <small>${result.message}</small>
                `;
            }
            
            // Supprimer l'ancien message s'il existe
            const oldMessage = formulaire.querySelector('.form-message');
            if (oldMessage) {
                oldMessage.remove();
            }
            
            messageDiv.classList.add('form-message');
            submitBtn.parentElement.parentElement.appendChild(messageDiv);
            
            // Retirer le message après 5 secondes
            setTimeout(() => {
                messageDiv.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => messageDiv.remove(), 300);
            }, 5000);
            
        } catch (error) {
            console.error('Erreur:', error);
            
            const errorDiv = document.createElement('div');
            errorDiv.style.cssText = `
                margin-top: 1rem;
                padding: 1rem;
                border-radius: 8px;
                background: rgba(255, 0, 0, 0.1);
                color: #dc3545;
                border: 2px solid #dc3545;
                text-align: center;
            `;
            errorDiv.innerHTML = `
                <strong>✗ Erreur de connexion</strong><br>
                <small>Impossible de contacter le serveur. Veuillez réessayer.</small>
            `;
            errorDiv.classList.add('form-message');
            
            const oldMessage = formulaire.querySelector('.form-message');
            if (oldMessage) oldMessage.remove();
            
            submitBtn.parentElement.parentElement.appendChild(errorDiv);
            
            setTimeout(() => errorDiv.remove(), 5000);
        } finally {
            // Réactiver le bouton
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
            submitBtn.style.cursor = 'pointer';
        }
    });
}

// Ajouter les animations CSS
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
`;
document.head.appendChild(style);


//MENU BURGER

const burger = document.getElementById('burger');
const dropdown = document.getElementById('menuDropdown');

burger.addEventListener('click', () => {
    dropdown.classList.toggle('open');
});


//CURSOR LIGHT FOR FUN
const cursorLight = document.querySelector('.cursor-light');

document.addEventListener('mousemove', (e) => {
    cursorLight.style.left = `${e.clientX}px`;
    cursorLight.style.top = `${e.clientY}px`;
});


//TARDIS
const tardisLink = document.getElementById('tardisScroll');
function updateTardisLink() {
  if (window.innerWidth <= 1000) {
    // MOBILE => scroll au header
    tardisLink.setAttribute('href', '#header__wrapper container');
    tardisLink.setAttribute('target', '_self');
  } else {
    // DESKTOP => redirection vers générique
    tardisLink.setAttribute('href', 'https://www.youtube.com/watch?v=vyPw25rYKFM&list=PLcWquS7QYEpQDoXYs0aKXn1J8MiIgobE5&index=1');
    tardisLink.setAttribute('target', '_blank');
  }
}
updateTardisLink();
window.addEventListener('resize', updateTardisLink);



//La navbar réagit à la section
const sections = document.querySelectorAll('section[id*="wrapper"]');
const navLinks = document.querySelectorAll('.nav-link');

const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    const id = entry.target.getAttribute('id');
    const link = document.querySelector(`.nav-link[href="#${id}"]`);

    if (entry.isIntersecting) {
      navLinks.forEach(l => l.classList.remove('active'));
      if (link) link.classList.add('active');
    }
  });
}, {
  root: null,
  rootMargin: '0px',
  threshold: 0.5
});

sections.forEach(section => {
  observer.observe(section);
});


//Easter egg

let input = "";
let jinxAudio = null;

window.addEventListener("keydown", (e) => {
  if (e.key === "Backspace") {
    input = input.slice(0, -1);
  } else {
    input += e.key.toLowerCase();
  }

  // Activer Jinx mode si le mot "jinx" est détecté
  if (input.includes("jinx")) {
    activateJinxMode();
  }

  if (input.length === 0) {
    deactivateJinxMode();
  }

  if (input.length > 10) {
    input = input.slice(-10);
  }
});

function activateJinxMode() {
  if (!document.body.classList.contains("jinx-mode")) {
    document.body.classList.add("jinx-mode");
    jinxAudio = new Audio('assets/audio/Jinx.mp3');
    jinxAudio.play();
  }
}

function deactivateJinxMode() {
  document.body.classList.remove("jinx-mode");
  if (jinxAudio) {
    jinxAudio.pause();
    jinxAudio.currentTime = 0;
  }
}