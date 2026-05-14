<script lang="ts">
  import AppHead from '@/components/AppHead.svelte';
  import { getCourse, submitAnswers } from '@/lib/course';
  import type { Answers } from '@/types';

  const questions = getCourse();

  let answers: Promise<Answers | undefined>;

  let form: HTMLFormElement | null;

  // yikes
  setTimeout(() => {
    form?.addEventListener("submit", (event) => {
      event.preventDefault();

      form && (answers = submitAnswers(form));
    });
  }, 1000);
</script>

<style>
  main {
    padding: 100px;
  }

  .course,
  .question,
  .choices {
    display: flex;
    flex-direction: column;
  }

  .course {
    gap: 20px;
  }

  .correct {
    color: lawngreen;
  }

  .incorrect {
    color: crimson;
  }

  input[type="text"] {
    border: solid 1px var(--primary);
  }

  input[type="submit"]:hover {
    cursor: pointer;
  }
</style>

<AppHead title="Course" />

<div>
  <main>
    <div>
      {#await questions}
	      <p>Loading questions...</p>

      {:then questions}
        {#if questions && !answers}
          <form bind:this={form} action="/course" method="POST" class="course" id="courseform">
            {#each questions as question}
              <div class="question">

                {#if question.type === 'multiple_choice'}
                  <fieldset>
                    <legend>{question.prompt}</legend>

                    <div class="choices">
                      {#each question.options as option}
                        <div class="choice">
                          <input type="radio" value={option.id} name={question.id} required />
                          <label for={option.id}>{option.label}</label>
                        </div>
                      {/each}
                    </div>
                  </fieldset>
                {/if}

                {#if question.type === 'open'}
                  <label for={question.id}>{question.prompt}</label>
                  <input
                    type="text"
                    id={question.id}
                    name={question.id}
                    required
                  />
                {/if}
              </div>
            {/each}

            <input type="submit" value="Antwoorden versturen" />
          </form>

        {:else}
          {#if !answers}
            <p>Hmm, no course. Tussenuur!</p>
          {/if}

        {/if}
      {:catch error}
        <p>Something went wrong: {error.message}</p>

      {/await}

      {#await answers}
        <p>Loading answers...</p>

      {:then answers}
        {#if answers}
          <p>Score:</p>
          <p>{answers.score} / {answers.questions.length}</p>
          {#each answers.questions as answer}
            <p class={answer.correct ? 'correct' : 'incorrect'}>{answer.prompt}</p>
          {/each}
        {/if}

      {:catch error}
        <p>Something went wrong: {error.message}</p>

      {/await}
    </div>
  </main>
</div>
