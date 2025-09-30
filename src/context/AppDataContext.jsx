import React, { createContext, useContext, useEffect, useMemo, useReducer } from 'react';
import { initialData } from '../utils/initialData.js';
import { createId } from '../utils/id.js';

const STORAGE_KEY = 'feroxz-state-v2';

const AppDataContext = createContext();

function loadState() {
  if (typeof window === 'undefined') {
    return initialData;
  }

  try {
    const stored = window.localStorage.getItem(STORAGE_KEY);
    if (stored) {
      const parsed = JSON.parse(stored);
      return {
        ...initialData,
        ...parsed,
        genetics: initialData.genetics
      };
    }
  } catch (error) {
    console.warn('Failed to parse stored FeroxZ data, falling back to defaults.', error);
  }
  return initialData;
}

function reducer(state, action) {
  switch (action.type) {
    case 'UPDATE_SETTINGS':
      return {
        ...state,
        settings: {
          ...state.settings,
          ...action.payload
        }
      };
    case 'UPSERT_ANIMAL': {
      const { animal } = action;
      const exists = state.animals.some((item) => item.id === animal.id);
      const nextAnimal = {
        ...animal,
        id: animal.id || createId('animal')
      };
      return {
        ...state,
        animals: exists
          ? state.animals.map((item) => (item.id === nextAnimal.id ? nextAnimal : item))
          : [...state.animals, nextAnimal]
      };
    }
    case 'DELETE_ANIMAL':
      return {
        ...state,
        animals: state.animals.filter((animal) => animal.id !== action.id)
      };
    case 'UPSERT_CARE_GUIDE': {
      const guide = action.guide;
      const normalized = {
        ...guide,
        id: guide.id || createId('care')
      };
      const exists = state.careGuides.some((item) => item.id === normalized.id);
      return {
        ...state,
        careGuides: exists
          ? state.careGuides.map((item) => (item.id === normalized.id ? normalized : item))
          : [...state.careGuides, normalized]
      };
    }
    default:
      return state;
  }
}

export function AppDataProvider({ children }) {
  const [state, dispatch] = useReducer(reducer, undefined, loadState);

  useEffect(() => {
    if (typeof window !== 'undefined') {
      const snapshot = {
        ...state,
        genetics: undefined
      };
      window.localStorage.setItem(STORAGE_KEY, JSON.stringify(snapshot));
    }
  }, [state]);

  const value = useMemo(() => ({ state, dispatch }), [state]);

  return <AppDataContext.Provider value={value}>{children}</AppDataContext.Provider>;
}

export function useAppData() {
  const context = useContext(AppDataContext);
  if (!context) {
    throw new Error('useAppData must be used within an AppDataProvider');
  }
  return context;
}
