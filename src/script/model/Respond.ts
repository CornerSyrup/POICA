export interface Respond {
  status: number;
}

declare interface SignInError {
  /**
   * Error message.
   */
  message: string;
  /**
   * Error code.
   */
  code: number;
}

export declare interface SignInRespond {
  /**
   * Status code of log in, 1 for logged in, 0 for logged out.
   */
  status: boolean;
  error?: SignInError;
}

declare interface SuicaRegisterError {
  /**
   * Error message.
   */
  message: string;
  /**
   * Error code.
   */
  code: number;
}

export declare interface SuicaRegisterRespond {
  /**
   * Status code of log in, 1 for logged in, 0 for logged out.
   */
  status: boolean;
  error?: SignInError;
}
