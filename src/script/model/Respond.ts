export default interface Respond {
    /**
     * Status code.
     */
    status: number;
}

interface SignInError {
    /**
     * Error message.
     */
    message: string;
    /**
     * Error code.
     */
    code: number;
}

export interface SignInRespond {
    /**
     * Status code of log in, 1 for logged in, 0 for logged out.
     */
    status: boolean;
    error?: SignInError;
}

interface SuicaRegisterError {
    /**
     * Error message.
     */
    message: string;
    /**
     * Error code.
     */
    code: number;
}

export interface SuicaRegisterRespond {
    /**
     * Status code of log in, 1 for logged in, 0 for logged out.
     */
    status: boolean;
    error?: SignInError;
}
