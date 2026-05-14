import type { Answers, Questions } from '@/types/course';

export async function getCourse(): Promise<Questions["questions"] | undefined> {
  try {
    const courseResponse = await fetch('http://localhost:8000/course');

    if (!courseResponse.ok) {
      throw new Error(`Response status: ${courseResponse.status}`);
    }

    const result: Questions = await courseResponse.json();

    return result.questions
  } catch (error) {
    if (error instanceof Error) {
      console.error(error.message);
    } else {
      console.error(String(error));
    }
  }
};

export async function submitAnswers(form: HTMLFormElement): Promise<Answers | undefined> {
  try {
    const formObject = Object.fromEntries(new FormData(form));

    const answerResponse = await fetch('http://localhost:8000/course', {
      method: "POST",
      body: JSON.stringify(formObject),
    });

    if (!answerResponse.ok) {
      throw new Error(`Response status: ${answerResponse.status}`);
    }

    const result: Answers = await answerResponse.json();

    return result
  } catch (error) {
    if (error instanceof Error) {
      console.error(error.message);
    } else {
      console.error(String(error));
    }
  }
};
