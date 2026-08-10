<div class="card box_birthday mb-2">
	<div class="card-body">
		<div class="happy">
			<div class="balloon balloon1 balloon-anim1"></div>
			<div class="balloon balloon2 balloon-anim6"></div>
			<div class="balloon balloon3 balloon-anim3"></div>
			<div class="balloon balloon4 balloon-anim4"></div>
			<div class="balloon balloon5 balloon-anim5"></div>
		</div>
		<div class="birthday">
			<div class="balloon balloon3 balloon-anim6"></div>
			<div class="balloon balloon1 balloon-anim2"></div>
			<div class="balloon balloon4 balloon-anim1"></div>
			<div class="balloon balloon2 balloon-anim6"></div>
			<div class="balloon balloon1 balloon-anim4"></div>
			<div class="balloon balloon3 balloon-anim5"></div>
			<div class="balloon balloon2 balloon-anim1"></div>
			<div class="balloon balloon4 balloon-anim6"></div>
		</div>
		<div class="name d-flex flex-column align-items-center fs-16 p-4">
			{foreach from=$birthdays item=birthday}
				<div class="staff_birthday">{$birthday.role_name} {$birthday.department_name} {$birthday.full_name}</div>
			{/foreach}
		
		</div>
	</div>
</div>
{literal}
<style>
	:root {
	  --balloon1-bg-color: #eaeaeae6;
	  --balloon2-bg-color: #e8dab2e6;
	  --balloon3-bg-color: #dd6e42e3;
	  --balloon4-bg-color: #4f6d7ad6;
	  --balloon5-bg-color: #eaeaeae6;
	  --bg-birthday-color: #c0d6df;
	}
	.staff_birthday:nth-child(2n+1){
		animation: color-change1 1s infinite;
	}
	.staff_birthday:nth-child(2n){
		animation: color-change2 1s infinite;
	}
	@keyframes color-change1 {
	  0% { color: red; }
	  50% { color: rebeccapurple; }
	  100% { color: orange; }
	}
	@keyframes color-change2 {
	  0% { color: green; }
	  50% { color: blue; }
	  100% { color: mediumvioletred; }
	}
	.box_birthday {
	  background: var(--bg-birthday-color);
	}

	.happy,
	.birthday {
	  flex-direction: row;
	  display: flex;
	  justify-content: center;
	}
	.name {
	  color: #4f6d7a;
	  font-family: "Comic Sans MS", Arial, Helvetica, sans-serif;
	}

	.balloon {
	  width: 31px;
	  height: 40px;
	  margin: 10px;
	  border-radius: 80%;
	  position: relative;
	}

	.balloon::after {
	  position: absolute;
	  bottom: -12px;
	  left: 40%;
	  content:
	  "\2713";
	  transform: rotate(180deg);
	  font-weight: bold;
	  font-size: 16px;
	}

	.happy .balloon::before,
	.birthday .balloon::before {
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  transform: translate(-50%,-50%);
	  font-size: 17px;
	  color: #4f6d7a;
	  font-family:
	  "Comic Sans MS", Arial, Helvetica, sans-serif;
	}

	.happy .balloon:nth-child(1)::before {
	  content: "H";
	}
	.happy .balloon:nth-child(2)::before {
	  content: "A";
	}
	.happy .balloon:nth-child(3)::before {
	  content: "P";
	}
	.happy .balloon:nth-child(4)::before {
	  content: "P";
	}
	.happy .balloon:nth-child(5)::before {
	  content: "Y";
	}
	.birthday .balloon:nth-child(1)::before {
	  content: "B";
	}
	.birthday .balloon:nth-child(2)::before {
	  content: "I";
	}
	.birthday .balloon:nth-child(3)::before {
	  content: "R";
	}
	.birthday .balloon:nth-child(4)::before {
	  content: "T";
	}
	.birthday .balloon:nth-child(5)::before {
	  content: "H";
	}
	.birthday .balloon:nth-child(6)::before {
	  content: "D";
	}
	.birthday .balloon:nth-child(7)::before {
	  content: "A";
	}
	.birthday .balloon:nth-child(8)::before {
	  content: "Y";
	}
	@keyframes balloon1 {
	  0%,
	  100% {
		transform: translate(0, 0) rotate(-10deg);
	  }
	  50% {
		transform: translate(0, 10px) rotate(10deg);
	  }
	}

	@keyframes balloon2 {
	  0%,
	  100% {
		transform: translate(-10px, 20px) rotate(15deg);
	  }
	  50% {
		transform: translate(0, -10px) rotate(-5deg);
	  }
	}

	@keyframes balloon3 {
	  0%,
	  100% {
		transform: translate(-10px, 0) rotate(-10deg);
	  }
	  50% {
		transform: translate(0, -5px) rotate(20deg);
	  }
	}

	@keyframes balloon4 {
	  0%,
	  100% {
		transform: translate(-10px, 0) rotate(0deg);
	  }
	  50% {
		transform: translate(0, 8px) rotate(30deg);
	  }
	}

	@keyframes balloon5 {
	  0%,
	  100% {
		transform: translate(10px, 0) rotate(20deg);
	  }
	  50% {
		transform: translate(0, 30px) rotate(-20deg);
	  }
	}
	@keyframes balloon6 {
	  0%,
	  100% {
		transform: translate(-10px, 0) rotate(0deg);
	  }
	  50% {
		transform: translate(0, 20px) rotate(30deg);
	  }
	}

	@keyframes balloon7 {
	  0%,
	  100% {
		transform: translate(0, 0) rotate(15deg);
	  }
	  50% {
		transform: translate(0, 0) rotate(-15deg);
	  }
	}
	.balloon1 {
	  background: var(--balloon1-bg-color);
	  box-shadow: inset 10px 10px 10px #c7c3c3e6;
	}
	.balloon1::after {
	  color: var(--balloon1-bg-color);
	}

	.balloon2 {
	  background: var(--balloon2-bg-color);
	  box-shadow: inset 10px 10px 10px #d5c7a1;
	}
	.balloon2::after {
	  color: var(--balloon2-bg-color);
	}

	.balloon3 {
	  background: var(--balloon3-bg-color);
	  box-shadow: inset 10px 10px 10px #cf6840;
	}
	.balloon3::after {
	  color: var(--balloon3-bg-color);
	}

	.balloon4 {
	  background: var(--balloon4-bg-color);
	  box-shadow: inset 10px 10px 10px #7ccbed;
	}
	.balloon4::after {
	  color: var(--balloon4-bg-color);
	}
	.balloon5 {
	  background: var(--balloon5-bg-color);
	  box-shadow: inset 10px 10px 10px #cbc8c8;
	}
	.balloon5::after {
	  color: var(--balloon5-bg-color);
	}
	.balloon-anim1 {
	  animation: balloon1 15s ease infinite;
	}
	.balloon-anim2 {
	  animation: balloon2 5s ease infinite;
	}
	.balloon-anim3 {
	  animation: balloon3 10s ease infinite;
	}
	.balloon-anim4 {
	  animation: balloon4 5s ease infinite;
	}
	.balloon-anim5 {
	  animation: balloon5 15s ease infinite;
	}
	.balloon-anim6 {
	  animation: balloon7 5s ease infinite;
	}

</style>
{/literal}