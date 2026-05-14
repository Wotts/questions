type MultipleChoiceAnswer = {
  id: string;
  label: string;
}

type MultipleChoiceQuestion = {
  id: string;
  type: 'multiple_choice';
  prompt: string;
  options: MultipleChoiceAnswer[];
}

type OpenQuestion = {
  id: string;
  type: 'open';
  prompt: string;
}

type ScoredQuestion = {
  id: string;
  prompt: string;
  correct: boolean;
}

export type Questions = {
  questions: (OpenQuestion | MultipleChoiceQuestion)[];
};

export type Answers = {
  score: number;
  questions: ScoredQuestion[];
}
