let fireCoolDown = false;

function openGame() {
  window.location.replace("gameArea.php");
}
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM yüklendi");
  playMusicBg();

  const currentPage = window.location.pathname;
  const currentPageName = currentPage.substring(
    currentPage.lastIndexOf("/") + 1
  );
  console.log(currentPageName);
  if (currentPageName == "gameArea.php") {
    startGame();
    createEnemy();
  } else if (currentPageName == "game.php") {
  }
});

function playMusicBg() {
  console.log("Music started");
  const audio = document.querySelector("#backgroundMusic");
  audio.volume = 0.1;
  audio.play();
}

function startGame() {
  const gameArea = document.querySelector(".gameArea");
  const hero = document.createElement("div");
  hero.classList.add("hero");
  gameArea.appendChild(hero);

  const heroWidth = hero.offsetWidth;
  const gameAreaWidth = gameArea.offsetWidth;
  const gameAreaHeight = gameArea.offsetHeight;

  let heroPosition = Math.random() * (gameAreaWidth - heroWidth);
  hero.style.left = heroPosition + "px";
  hero.style.top = "700px";
  let isMovingLeft = false;
  let isMovingRight = false;

  document.addEventListener("keydown", (event) => {
    if (event.key === "ArrowLeft") {
      isMovingLeft = true;
    } else if (event.key === "ArrowRight") {
      isMovingRight = true;
    }
  });

  document.addEventListener("keyup", (event) => {
    if (event.key === "ArrowLeft") {
      isMovingLeft = false;
    } else if (event.key === "ArrowRight") {
      isMovingRight = false;
    } else if (event.code === "Space" && !fireCoolDown) {
      fire();
    }
  });

  function fire() {
    if (!fireCoolDown) {
      const fire = document.createElement("div");
      fire.classList.add("fire");
      gameArea.appendChild(fire);

      const heroLeft = parseInt(hero.style.left || 0) + heroWidth / 2;
      const fireSpeed = 10;

      fire.style.left = heroLeft - 120 + "px";
      fire.style.top = "700px";

      function movefire() {
        const fireTop = parseInt(fire.style.top || 0);
        const fireLeft = parseInt(fire.style.left || 0);
        const fireRight = fireLeft + fire.offsetWidth;

        const enemies = document.querySelectorAll(".enemy");
        enemies.forEach((enemy) => {
          const enemyTop = parseInt(enemy.style.top || 0);
          const enemyLeft = parseInt(enemy.style.left || 0);
          const enemyRight = enemyLeft + enemy.offsetWidth;
          const enemyBottom = enemyTop + enemy.offsetHeight;

          if (
            fireTop <= enemyBottom &&
            fireRight >= enemyLeft &&
            fireLeft <= enemyRight
          ) {
            gameArea.removeChild(enemy);
            gameArea.removeChild(fire);
            createEnemy();
            clearInterval(fireInterval);
            fireCoolDown = false;
          }
        });

        if (fireTop > 0) {
          fire.style.top = fireTop - fireSpeed + "px";
        } else {
          gameArea.removeChild(fire);
          clearInterval(fireInterval);
          fireCoolDown = false;
        }
      }

      const fireInterval = setInterval(movefire, 20);

      fireCoolDown = true;
      setTimeout(() => {
        console.log("fire ateşlendi");
        fireCoolDown = false;
      }, 1000);
    }
  }

  function moveHero() {
    if (isMovingLeft && parseInt(hero.style.left || 0) > 0) {
      hero.style.left = parseInt(hero.style.left || 0) - 5 + "px";
    }
    if (
      isMovingRight &&
      parseInt(hero.style.left || 0) + heroWidth < gameAreaWidth
    ) {
      hero.style.left = parseInt(hero.style.left || 0) + 5 + "px";
    }
  }

  setInterval(moveHero, 20);
}

function createEnemy() {
  const gameArea = document.querySelector(".gameArea");
  const enemy = document.createElement("div");
  enemy.classList.add("enemy");
  gameArea.appendChild(enemy);

  const enemyWidth = enemy.offsetWidth;
  const gameAreaWidth = gameArea.offsetWidth;

  let enemyPosition = Math.random() * (gameAreaWidth - enemyWidth);
  let moveDirection = 1;
  enemy.style.left = enemyPosition + "px";
  enemy.style.top = "0px";
  function moveEnemy() {
    const enemyLeft = parseInt(enemy.style.left);

    if (enemyLeft >= gameAreaWidth - enemyWidth) {
      moveDirection = -1;
    } else if (enemyLeft <= 0) {
      moveDirection = 1;
    }

    enemy.style.left = enemyLeft + moveDirection * 5 + "px";
  }

  setInterval(moveEnemy, 20);
}
